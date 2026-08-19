<?php

/** Goal: Display User management table with roles, Caller: routes/web.php (users.index), Deps: User, Role */

namespace App\Livewire\PowergridTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;
use Spatie\Permission\Models\Role;

final class UserTable extends PowerGridComponent
{
    public string $tableName = 'UserTable';

    public bool $deferLoading = true;

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showSoftDeletes()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive(),
        ];
    }

    public function datasource(): Builder
    {
        return User::query()
            ->with(['roles'])
            ->orderByDesc('is_active')
            ->orderBy('name', 'asc');
    }

    public function relationSearch(): array
    {
        return [
            'roles' => ['name'],
        ];
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name', fn ($row) => view('components.dashboard.date-w-name', [
                'date' => $row->name,
                'name' => $row->email,
                'is_active' => (bool) $row->is_active,
            ]))
            ->add('email')
            ->add('roles_formatted', fn ($row) => $row->roles->pluck('name')->implode(', ') ?: '-')
            ->add('is_active')
            ->add('is_active_formatted', fn ($row) => view('components.table-component.badge-status', [
                'active' => (bool) $row->is_active,
                'label_active' => 'Active',
                'label_inactive' => 'Inactive',
            ])->render())
            ->add('created_at_formatted', fn ($row) => Carbon::parse($row->created_at)
                ->locale('id')
                ->isoFormat('DD MMM YYYY HH:mm'));
    }

    public function columns(): array
    {
        return [
            Column::action('Action')
                ->bodyAttribute('text-center'),

            Column::make('User ID', 'id')
                ->sortable(),

            Column::make('Nama', 'name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'email')
                ->hidden()
                ->searchable(),

            Column::make('Roles', 'roles_formatted'),

            Column::make('Status', 'is_active_formatted', 'is_active')
                ->sortable(),

            Column::make('Dibuat', 'created_at_formatted', 'created_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        $roles = Role::select(['id', 'name'])->get();

        return [
            Filter::inputText('id', 'users.id')
                ->placeholder('User ID'),

            Filter::inputText('name', 'users.name')
                ->placeholder('Nama'),

            Filter::boolean('is_active', 'users.is_active')
                ->label('Aktif', 'Tidak aktif'),

            Filter::select('roles_formatted', 'role_id')
                ->dataSource(collect($roles))
                ->optionLabel('name')
                ->optionValue('id')
                ->builder(fn (Builder $query, $value) => $query->whereHas('roles', fn ($q) => $q->where('id', $value))),
        ];
    }

    public function actionsFromView(User $row)
    {
        return Blade::render("
            <x-button.primary href=\"{{ route('users.edit', \$row->id) }}\" wire:navigate>
                Edit
            </x-button.primary>
        ", ['row' => $row]);
    }

    public function queryString(): array
    {
        return $this->powerGridQueryString();
    }
}
