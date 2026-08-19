<?php

namespace App\Livewire\PowergridTables;

use App\Models\ApiClient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Blade;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\Facades\Filter;
use PowerComponents\LivewirePowerGrid\Facades\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\PowerGridFields;

final class ApiClientTable extends PowerGridComponent
{
    public string $tableName = 'ApiClientTable';

    public bool $deferLoading = true;

    public function setUp(): array
    {
        return [
            PowerGrid::header()
                ->showSearchInput()
                ->showToggleColumns(),
            PowerGrid::footer()
                ->showPerPage()
                ->showRecordCount(),
            PowerGrid::responsive(),
        ];
    }

    public function datasource(): Builder
    {
        return ApiClient::query()
            ->orderByDesc('is_active')
            ->orderBy('name', 'asc');
    }

    public function fields(): PowerGridFields
    {
        return PowerGrid::fields()
            ->add('id')
            ->add('name')
            ->add('slug')
            ->add('api_key', fn ($row) => '<code class="px-2 py-1 bg-zinc-100 dark:bg-zinc-800 text-red-600 dark:text-red-400 font-mono text-xs rounded border border-zinc-200 dark:border-zinc-700 select-all">' . e($row->api_key) . '</code>')
            ->add('rate_limit_per_minute', fn ($row) => '<span class="font-semibold">' . $row->rate_limit_per_minute . '</span> req/min')
            ->add('is_active')
            ->add('is_active_formatted', fn ($row) => view('components.table-component.badge-status', [
                'active' => (bool) $row->is_active,
                'label_active' => 'Active',
                'label_inactive' => 'Inactive',
            ])->render())
            ->add('last_used_at_formatted', fn ($row) => $row->last_used_at
                ? Carbon::parse($row->last_used_at)->locale('id')->diffForHumans()
                : '<span class="text-zinc-400 italic">Belum pernah</span>')
            ->add('created_at_formatted', fn ($row) => Carbon::parse($row->created_at)
                ->locale('id')
                ->isoFormat('DD MMM YYYY'));
    }

    public function columns(): array
    {
        return [
            Column::action('Action')
                ->bodyAttribute('text-center'),

            Column::make('ID', 'id')
                ->sortable(),

            Column::make('Nama Aplikasi / Client', 'name')
                ->sortable()
                ->searchable(),

            Column::make('API Key', 'api_key')
                ->searchable(),

            Column::make('Rate Limit', 'rate_limit_per_minute')
                ->sortable(),

            Column::make('Status', 'is_active_formatted', 'is_active')
                ->sortable(),

            Column::make('Terakhir Digunakan', 'last_used_at_formatted', 'last_used_at')
                ->sortable(),

            Column::make('Dibuat', 'created_at_formatted', 'created_at')
                ->sortable(),
        ];
    }

    public function filters(): array
    {
        return [
            Filter::inputText('name', 'api_clients.name')
                ->placeholder('Cari nama client...'),

            Filter::boolean('is_active', 'api_clients.is_active')
                ->label('Aktif', 'Tidak aktif'),
        ];
    }

    public function actionsFromView(ApiClient $row)
    {
        return Blade::render("
            <div class=\"flex items-center justify-center gap-2\">
                <x-button.primary href=\"{{ route('api-clients.edit', \$row->id) }}\" wire:navigate class=\"text-xs py-1.5 px-3\">
                    Edit
                </x-button.primary>
            </div>
        ", ['row' => $row]);
    }

    public function queryString(): array
    {
        return $this->powerGridQueryString();
    }
}
