<?php

namespace App\Livewire\PowergridTables;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Spatie\Permission\Models\Role;

final class RolesTable extends PowerGridComponent
{
    public string $tableName = 'RolesTable';

    public bool $deferLoading = true;

    public bool $showFilters = false;

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSoftDeletes()
                ->showSearchInput(),
            PowerGrid::footer()
                ->showPerPage(25, [0, 10, 25, 50, 500])
                ->showRecordCount(),
            PowerGrid::responsive()
                ->fixedColumns('name', 'guard_name', 'created_at', 'updated_at'),
        ];
    }

    public function datasource(): Builder
    {
        return Role::query()
            ->withCount('users')
            ->with('permissions')
            ->orderBy('name', 'asc');
    }

    public function relationSearch(): array
    {
        return [];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('guard_name')
            ->add('permissions', function ($query) {
                $permissions = $query->permissions
                    ->pluck('name')
                    ->sort()
                    ->values();

                $data = $permissions->count() > 5 ? $permissions->take(5)->push('...') : $permissions;

                return view('components.table-component.tags', ['items' => $data]);
            })
            ->add('users_count')
            ->add('users_count_formatted', function ($query) {
                return '<span class="rounded-lg bg-blue-100 px-2.5 py-1 text-xs font-semibold text-blue-800 dark:bg-blue-900 dark:text-blue-200">'.$query->users_count.' Pengguna</span>';
            })
            ->add('created_at')
            ->add('created_at_formatted', function ($query) {
                $date = Carbon::parse($query->created_at)->locale('id')->isoFormat('D MMMM YYYY');
                $time = Carbon::parse($query->created_at)->locale('id')->isoFormat('HH:mm:ss');

                return view('components.dashboard.custom-date', ['date' => $date, 'time' => $time]);
            });
    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            Column::make('Nama Role', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Guard', 'guard_name')
                ->sortable(),
            Column::make('Jumlah Pengguna', 'users_count_formatted', 'users_count')
                ->sortable(),
            Column::make('Permissions', 'permissions'),
            Column::make('Created at', 'created_at_formatted', 'created_at'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'name'),
            Filter::datepicker('created_at', 'created_at'),
        ];
    }

    public function actionsFromView(Role $row)
    {
        return Blade::render("
            <x-button.primary href=\"{{ route('roles.edit', \$row->id) }}\" wire:navigate>
                Edit
            </x-button.primary>
        ", ['row' => $row]);
    }

    public function queryString(): array
    {
        return $this->powerGridQueryString();
    }
}
