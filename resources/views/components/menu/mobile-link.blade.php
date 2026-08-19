<a href="{{ $href }}"
    {{ $attributes->merge(['class' => 'group mb-2 flex h-full w-auto flex-col items-center justify-center rounded-lg border border-zinc-200 bg-zinc-50 p-2 text-zinc-800 transition duration-500 hover:scale-95 hover:bg-zinc-100 hover:shadow-md dark:border-zinc-800 dark:bg-zinc-900 dark:text-white hover:dark:bg-zinc-800']) }}>
    {{ $slot }}
    <p class="mt-1 text-center !text-xs">{{ $label }}</p>
</a>
