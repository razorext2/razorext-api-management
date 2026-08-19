@props([
    'id',
    'name',
    'options' => [],
    'defaultOption' => null,
    'textLabel' => null,
    'class' => null,
    'value' => '',
    'labels' => true,
])

@if ($labels)
    <label class="mb-2 block text-sm font-medium text-zinc-900 dark:text-white" for="{{ $id }}">
        {{ $label ?? ($textLabel ?? null) }}
    </label>
@endif

<select
    {{ $attributes->merge(['class' => ($class ?? null) . ' block w-full rounded-lg border border-zinc-200 bg-zinc-50 p-2.5 text-sm text-zinc-900 focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:placeholder-zinc-500 dark:focus:border-blue-500 dark:focus:ring-blue-500']) }}
    id="{{ $id }}" name="{{ $name }}">
    @if ($defaultOption)
        <option value="">{{ $defaultOption }}</option>
    @endif
    @foreach ($options as $item => $labelValue)
        <option value="{{ $item }}" {{ $item == $value ? 'selected' : '' }}>
            {{ $labelValue }}
        </option>
    @endforeach
    {{ $slot }}
</select>
