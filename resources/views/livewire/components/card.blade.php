<div class="relative w-full overflow-x-auto xl:overflow-x-visible">

    <div class="flex snap-x snap-mandatory flex-nowrap gap-4 xl:flex-wrap" wire:poll.300s>

        @foreach ($data as $row)
            @if ($row['permission'] == 'all' || auth()->user()->hasPermissionTo($row['permission']))
                <x-card.card-carousel-item :label="$row['label']" :count="$row['count']" :indicator="$row['indicator']" :icon="$row['icon']"
                    :color="$row['color']" :visibleCount="$totalData" />
            @endif
        @endforeach

    </div>

</div>
