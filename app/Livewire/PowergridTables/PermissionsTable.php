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
use Spatie\Permission\Models\Permission;

final class PermissionsTable extends PowerGridComponent
{
    public string $tableName = 'PermissionsTable';

    public bool $deferLoading = true;

    public bool $showFilters = true;

    public function setUp(): array
    {
        $this->showCheckBox();

        return [
            PowerGrid::header()
                ->showSoftDeletes()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage(25, [0, 10, 25, 50, 500])
                ->showRecordCount(),
            PowerGrid::responsive(),
        ];
    }

    public function datasource(): Builder
    {
        return Permission::query()
            ->with('roles')
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
            ->add('roles', function ($query) {
                $roles = $query->roles
                    ->pluck('name')
                    ->sort()
                    ->values();

                $data = $roles->count() > 5 ? $roles->take(5)->push('...') : $roles;

                return view('components.table-component.tags', ['items' => $data]);
            })
            ->add('created_at')
            ->add('created_at_formatted', function ($query) {
                $date = Carbon::parse($query->created_at)->locale('id')->isoFormat('D MMMM YYYY');
                $time = Carbon::parse($query->created_at)->locale('id')->isoFormat('HH:mm:ss');

                return view('components.dashboard.custom-date', ['date' => $date, 'time' => $time]);
            })
            ->add('updated_at')
            ->add('updated_at_formatted', function ($query) {
                $date = Carbon::parse($query->updated_at)->locale('id')->isoFormat('D MMMM YYYY');
                $time = Carbon::parse($query->updated_at)->locale('id')->isoFormat('HH:mm:ss');

                return view('components.dashboard.custom-date', ['date' => $date, 'time' => $time]);
            });

    }

    public function columns(): array
    {
        return [
            Column::action('Action'),
            Column::make('Name', 'name')
                ->sortable()
                ->searchable(),
            Column::make('Guard', 'guard_name')
                ->sortable()
                ->searchable(),
            Column::make('Roles', 'roles'),
            Column::make('Created at', 'created_at_formatted', 'created_at'),
            Column::make('Updated at', 'updated_at_formatted', 'updated_at'),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'name'),
            Filter::datetimepicker('created_at', 'created_at'),
        ];
    }

    public function actionsFromView(Permission $row)
    {
        return Blade::render("
            <x-button.primary href=\"{{ route('permissions.edit', \$row->id) }}\" wire:navigate>
                Edit
            </x-button.primary>
        ", ['row' => $row]);
    }

    public function queryString(): array
    {
        return $this->powerGridQueryString();
    }
}
