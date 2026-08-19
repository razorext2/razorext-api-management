<?php

/** Goal: PowerGrid table for announcement management, Caller: dashboard/announcement/index.blade.php, Deps: Announcement model */

namespace App\Livewire\PowergridTables;

use App\Models\Announcement;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Livewire\Attributes\On;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class AnnouncementTable extends PowerGridComponent
{
    public string $tableName = 'AnnouncementTable';

    public bool $deferLoading = true;

    public bool $showFilters = true;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::responsive(),
            PowerGrid::footer()
                ->showPerPage(perPage: 10, perPageValues: [10, 25, 50, 100, 500, 0])
                ->showRecordCount(),
        ];
    }

    public function header(): array
    {
        return [];
    }

    public function datasource(): Builder
    {
        return Announcement::query();
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('title')
            ->add('description')
            ->add('status', function ($row) {
                return view('components.table-component.status', ['status' => $row->status]);
            })
            ->add('updated_at')
            ->add('updated_at_formatted', function (Announcement $row) {
                return Carbon::parse($row->updated_at)->locale('id')->diffForHumans();
            });
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),

            Column::make('Judul', 'title')
                ->sortable()
                ->searchable(),

            Column::make('Status', 'status')
                ->sortable()
                ->searchable(),

            Column::make('Deskripsi', 'description')
                ->sortable()
                ->searchable(),

            Column::make('Updated at', 'updated_at_formatted', 'updated_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::boolean('status')
                ->label('Aktif', 'Tidak aktif'),
            Filter::inputText('title', 'title'),
        ];
    }

    public function actions(Announcement $row): array
    {
        return [];
    }

    public function actionsFromView(Announcement $row): View
    {
        $actions = [
            [
                'id' => 'state-btn',
                'action' => "javascript:Livewire.dispatch('changeStatus', { id: $row->id })",
                'label' => 'Ubah Status',
            ],
            [
                'id' => 'edit-btn',
                'action' => route('announcement.edit', $row->id),
                'label' => 'Edit',
                'navigate' => true,
            ],
        ];

        return view('components.dashboard.action-buttons', [
            'id' => $row->id,
            'datas' => $actions,
            'delete' => true,
        ]);
    }

    #[On('changeStatus')]
    public function changeStatus(int $id)
    {
        $announcement = Announcement::find($id);
        if ($announcement) {
            $announcement->update([
                'status' => $announcement->status == 1 ? 0 : 1,
            ]);
            $this->dispatch('pg:eventRefresh-AnnouncementTable');
        }
    }

    #[On('delete')]
    public function delete(int $id)
    {
        $announcement = Announcement::find($id);
        if ($announcement) {
            $announcement->delete();
            $this->dispatch('pg:eventRefresh-AnnouncementTable');
        }
    }

    public function queryString(): array
    {
        return $this->powerGridQueryString();
    }
}
