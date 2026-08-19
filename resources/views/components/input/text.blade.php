@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge([
    'class' => 'rounded-lg border-zinc-200 bg-white text-zinc-900 focus:border-blue-500 focus:ring-blue-500 dark:border-zinc-800 dark:bg-zinc-900 dark:text-white dark:focus:border-blue-500 dark:focus:ring-blue-500',
]) !!}>
