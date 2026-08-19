@if ($paginator->hasPages())
    <nav class="flex flex-col items-center justify-between gap-4 sm:flex-row" role="navigation">

        {{-- Info teks --}}
        <p class="text-xs text-zinc-500 dark:text-zinc-400">
            @if ($paginator->firstItem())
                <span class="font-bold text-zinc-700 dark:text-zinc-300">{{ $paginator->firstItem() }}</span>
                -
                <span class="font-bold text-zinc-700 dark:text-zinc-300">{{ $paginator->lastItem() }}</span>
                dari
                <span class="font-bold text-zinc-700 dark:text-zinc-300">{{ $paginator->total() }}</span>
            @endif
        </p>

        {{-- Navigasi Angka --}}
        <div class="flex flex-nowrap items-center gap-1.5">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span
                    class="flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-300 dark:border-zinc-800 dark:text-zinc-700"
    x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-lg shadow-red-500/10' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                    <x-icons.chevron-left class="h-4 w-4" />
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                    class="flex h-8 w-8 items-center justify-center rounded-xl border border-zinc-200 bg-white text-zinc-500 transition-all hover:border-red-500 hover:text-red-600 dark:border-zinc-800 dark:bg-dark-primary dark:text-zinc-400 dark:hover:border-red-500">
                    <x-icons.chevron-left class="h-4 w-4" />
                </a>
            @endif

            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
                $sidePages = 1; // Jumlah halaman di kiri & kanan current page

                // Logic penentuan index yang harus tampil
                $pagesToShow = [];

                // Selalu tampilkan halaman 1 & 2 (atau 1,2,3 jika di awal)
                $pagesToShow[] = 1;
                if ($lastPage >= 2) {
                    $pagesToShow[] = 2;
                }

                // Tambahkan halaman di sekitar current page
                for ($i = max(1, $currentPage - $sidePages); $i <= min($lastPage, $currentPage + $sidePages); $i++) {
                    $pagesToShow[] = $i;
                }

                // Selalu tampilkan 2 halaman terakhir
                if ($lastPage >= 1) {
                    $pagesToShow[] = $lastPage;
                }
                if ($lastPage >= 2) {
                    $pagesToShow[] = $lastPage - 1;
                }

                // Unique & Sort
                $pagesToShow = array_unique($pagesToShow);
                sort($pagesToShow);
            @endphp

            @foreach ($pagesToShow as $index => $page)
                {{-- Tambahkan Titik-titik jika ada lompatan angka --}}
                @if ($index > 0 && $page - $pagesToShow[$index - 1] > 1)
                    <span
                        class="flex h-8 w-5 items-center justify-center text-[10px] font-bold text-zinc-400">...</span>
                @endif

                @if ($page == $currentPage)
                    <span
                        class="flex h-8 min-w-[2rem] items-center justify-center rounded-xl bg-red-600 px-2 text-xs font-black text-white shadow-lg shadow-red-500/20">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}"
                        class="flex h-8 min-w-[2rem] items-center justify-center rounded-xl border border-zinc-200 bg-white px-2 text-xs font-bold text-zinc-600 transition-all hover:border-red-500/50 hover:text-red-600 dark:border-zinc-800 dark:bg-dark-primary dark:text-zinc-400 dark:hover:text-red-400">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                    class="flex h-8 w-8 items-center justify-center rounded-xl border border-zinc-200 bg-white text-zinc-500 transition-all hover:border-red-500 hover:text-red-600 dark:border-zinc-800 dark:bg-dark-primary dark:text-zinc-400 dark:hover:border-red-500">
                    <x-icons.chevron-right class="h-4 w-4" />
                </a>
            @else
                <span
                    class="flex h-8 w-8 cursor-not-allowed items-center justify-center rounded-xl border border-zinc-200 bg-zinc-50 text-zinc-300 dark:border-zinc-800 dark:text-zinc-700"
    x-bind:class="dynamicBg ? 'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-lg shadow-red-500/10' : 'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
                    <x-icons.chevron-right class="h-4 w-4" />
                </span>
            @endif
        </div>
    </nav>
@endif
