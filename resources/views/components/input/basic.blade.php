@props([
    'class' => '',
    'id' => null,
    'name' => null,
    'label' => null,
    'labels' => true,
    'type' => 'text',
    'default' => null,
])

@php
    $inputName = $name ?? $attributes->whereStartsWith('wire:model')->first() ?? $attributes->get('name');
    $inputId = $id ?? $attributes->get('id') ?? $inputName ?? \Illuminate\Support\Str::random(8);
    $labelText = $label ?? ($slot->isNotEmpty() ? $slot : null);
@endphp

@if ($labels && $labelText)
    <label class="mb-2 block text-sm font-medium text-zinc-900 dark:text-white"
        for="{{ $inputId }}">{{ $labelText }}</label>
@endif

<input
    class="{{ $class }} block w-full rounded-lg border border-zinc-200 bg-zinc-50 p-2.5 text-sm text-zinc-900 focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:placeholder-zinc-500 dark:focus:border-blue-500 dark:focus:ring-blue-500"
    id="{{ $inputId }}" @if($inputName) name="{{ $inputName }}" @endif type="{{ $type }}" default="{{ $default }}"
    {{ $attributes }} />
