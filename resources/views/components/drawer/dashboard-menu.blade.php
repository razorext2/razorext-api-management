<div class="grid cursor-pointer grid-cols-3 items-center justify-between gap-2 text-zinc-500">

    @php $hasVisibleLinks = false; @endphp

    @foreach ($links as $item)
        @php
            // Unified guard check
            $guard = $item['guard'] ?? null;
            $canSee = match (true) {
                $guard === null => true,
                $guard[0] === 'any_permission' => auth()->user()->hasAnyPermission($guard[1]),
                $guard[0] === 'role' => auth()->user()->hasRole($guard[1]),
                $guard[0] === 'can' => auth()->user()->can($guard[1]),
                default => true,
            };
        @endphp

        @if ($canSee)
            @php $hasVisibleLinks = true; @endphp
            <x-menu.mobile-link href="{{ route($item['link']) }}" :label="$item['mobile_label'] ?? $item['label']">
                <x-dynamic-component :component="'icons.' . $item['icon']" class="h-7 w-7 text-red-500" />
            </x-menu.mobile-link>
        @endif
    @endforeach

    @if (!$hasVisibleLinks)
        <div class="col-span-3 flex items-center">
            <span class="text-center font-semibold text-red-500">
                Anda belum memiliki akses ke menu apapun.
            </span>
        </div>
    @endif

</div>
