<?php

/** Goal: Display system audit log table with user actions, Caller: routes/web.php (log.index), Deps: LogHistory, User */

namespace App\Livewire\PowergridTables;

use App\Models\LogHistory;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class LogTable extends PowerGridComponent
{
    public string $tableName = 'LogTable';

    public bool $deferLoading = true;

    public bool $showFilters = true;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showSoftDeletes(true)
                ->showToggleColumns(),
            PowerGrid::responsive()
                ->fixedColumns('actions', 'user_name'),
            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 25, 50, 100, 500, 0])
                ->showRecordCount(),
        ];
    }

    public function btnClass()
    {
        return 'liquid-btn inline-flex items-center gap-2 rounded-xl bg-red-600 px-3 py-1.5 text-xs font-semibold text-white transition-all hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800 me-0.5';
    }

    public function header(): array
    {
        return [
            Button::add('bulk-delete')
                ->slot('Bulk delete')
                ->class($this->btnClass())
                ->dispatch('bulkDelete.'.$this->tableName, []),
        ];
    }

    public function datasource(): Builder
    {
        return LogHistory::query()
            ->with('user:id,name,is_active')
            ->latest();
    }

    public function relationSearch(): array
    {
        return [
            'user' => [
                'name',
            ],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('user_name', function ($query) {
                return view('components.dashboard.name-w-badge', [
                    'name' => $query->user->name ?? 'System',
                    'is_active' => (bool) ($query->user->is_active ?? true),
                ]);
            })
            ->add('user_action')
            ->add('ip_address')
            ->add('user_agent')
            ->add('user_location')
            ->add('created_at', fn ($query) => Carbon::parse($query->created_at)->locale('id'));
    }

    public function columns(): array
    {
        return [
            Column::make('#', 'id')
                ->index(),
            Column::action('Action'),
            Column::make('Created At', 'created_at')
                ->sortable()
                ->searchable(),
            Column::make('Nama User', 'user_name'),
            Column::make('User Action', 'user_action')
                ->sortable()
                ->searchable(),
            Column::make('IP Address', 'ip_address')
                ->sortable()
                ->searchable(),
            Column::make('User Agent', 'user_agent')
                ->sortable()
                ->searchable(),
        ];
    }

    public function filters(): array
    {
        $users = User::select(['id', 'name'])
            ->whereHas('logs')
            ->orderBy('name', 'asc')
            ->get();

        return [
            Filter::select('user_name', 'user_id')
                ->dataSource(collect($users))
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::select('user_action', 'user_action')
                ->dataSource([
                    ['id' => 'login', 'name' => 'Login'],
                    ['id' => 'logout', 'name' => 'Logout'],
                    ['id' => 'create', 'name' => 'Create'],
                    ['id' => 'update', 'name' => 'Update'],
                    ['id' => 'delete', 'name' => 'Delete'],
                ])
                ->optionLabel('name')
                ->optionValue('id'),
            Filter::datetimepicker('created_at', 'created_at')
                ->params([
                    'timezone' => 'Asia/Jakarta',
                ]),
        ];
    }

    public function actionsFromView(LogHistory $row)
    {
        return Blade::render("
            <x-button.danger wire:click=\"\$dispatch('delete', { id: {{ \$row->id }} })\" :iconOnly=\"true\">
                <x-slot name='icon'>
                    <x-icons.trash-bin class='h-4 w-4' />
                </x-slot>
            </x-button.danger>
        ", ['row' => $row]);
    }

    #[On('delete')]
    public function delete(int $id): void
    {
        $this->dispatch('confirmDelete', id: $id);
    }

    #[On('confirmDeleteAction')]
    public function confirmDelete(int $id): void
    {
        $data = LogHistory::find($id);

        if (! $data) {
            $this->dispatch(
                'swal',
                title: 'Gagal!',
                text: "Terjadi kesalahan saat menghapus data dengan ID <b>$id</b>",
                icon: 'error'
            );

            return;
        }

        $data->delete();

        $this->dispatch(
            'swal',
            title: 'Terhapus!',
            text: 'Data yang dipilih berhasil dihapus.',
            icon: 'success'
        );
    }

    #[On('bulkDelete.{tableName}')]
    public function bulkDelete(): void
    {
        if (! $this->checkboxValues) {
            $this->dispatch(
                'swal',
                title: 'Gagal!',
                text: 'Tidak ada data yang dipilih.',
                icon: 'error'
            );

            return;
        }

        $this->dispatch('confirmBulkDelete', id: $this->checkboxValues, tableName: $this->tableName);
    }

    #[On('confirmBulkDeleteAction.{tableName}')]
    public function confirmBulkDelete(): void
    {
        LogHistory::destroy($this->checkboxValues);
        $this->js('window.pgBulkActions.clearAll()');

        $this->dispatch(
            'swal',
            title: 'Terhapus!',
            text: 'Data yang dipilih berhasil dihapus.',
            icon: 'success'
        );
    }

    public function queryString(): array
    {
        return $this->powerGridQueryString();
    }
}
