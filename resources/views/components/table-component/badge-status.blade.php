{{-- Goal: Badge status boolean (active/inactive) yang reusable, Livewire: UserTable, Alpine: - --}}
@props([
    'active' => false,
    'label_active' => 'Active',
    'label_inactive' => 'Inactive',
])

<span @class([
    'inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium',
    'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' => $active,
    'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' => !$active,
])>
    {{ $active ? $label_active : $label_inactive }}
</span>
