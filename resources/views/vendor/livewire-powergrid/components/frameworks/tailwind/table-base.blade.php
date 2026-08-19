@php
    $columns = collect($columns)->map(function ($column) {
        return data_forget($column, 'rawQueries');
    });
@endphp

<div class="flex flex-col rounded-xl border border-zinc-200 p-2 shadow-sm lg:p-4 dark:border-zinc-800"
    x-bind:class="dynamicBg ?
        'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
        'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'"
    @if ($deferLoading) wire:init="fetchDatasource" @endif>
    <div id="power-grid-table-container" class="{{ theme_style($theme, 'table.layout.container') }}">
        <div id="power-grid-table-base" class="{{ theme_style($theme, 'table.layout.base') }}">
            @include(theme_style($theme, 'layout.header'), [
                'enabledFilters' => $enabledFilters,
            ])

            @if (config('livewire-powergrid.filter') === 'outside')
                @php
                    $filtersFromColumns = $columns->filter(fn($column) => filled(data_get($column, 'filters')));
                @endphp

                @includeWhen(
                    $filtersFromColumns->count() > 0,
                    'livewire-powergrid::components.frameworks.tailwind.filter')
            @endif

            <div @class([
                'overflow-auto' => $readyToLoad,
                'overflow-hidden' => !$readyToLoad,
                theme_style($theme, 'table.layout.div'),
            ])>
                @include($table)
            </div>

            @include(theme_style($theme, 'footer.view'))
        </div>
    </div>
</div>
