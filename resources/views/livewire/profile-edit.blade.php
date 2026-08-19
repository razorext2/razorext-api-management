<div>
    {{-- Hero Profile Header --}}
    <div class="relative z-30 mb-6 rounded-xl border border-zinc-200 shadow-md dark:border-zinc-800"
        x-bind:class="dynamicBg ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
        {{-- Decorative gradient background --}}
        <div
            class="pointer-events-none absolute inset-0 rounded-xl bg-linear-to-br from-red-600/10 via-transparent to-transparent dark:from-red-900/20">
        </div>

        <div class="relative flex flex-col gap-6 p-6 sm:flex-row sm:items-end sm:p-8">
            {{-- Avatar Section --}}
            <div class="group relative w-fit" x-data="{ open: false }">
                <div class="relative">
                    <img class="h-28 w-28 rounded-2xl border-4 border-white object-cover shadow-xl dark:border-zinc-800 sm:h-32 sm:w-32"
                        src="{{ auth()->user()->profile_pic ? asset('storage/profile-pictures/' . auth()->user()->profile_pic) : asset('images/defaults/profile-picture-5.jpg') }}"
                        alt="{{ auth()->user()->name }}" onerror="this.src = '{{ asset('images/defaults/noImage.webp') }}'">
                    {{-- Edit overlay --}}
                    <x-button.secondary @click="open = !open"
                        class="absolute! inset-0! flex! items-center! justify-center! rounded-2xl! border-none! bg-zinc-950/50! opacity-0! shadow-none! ring-0! transition-opacity! duration-200! group-hover:opacity-100!"
                        type="button">
                        <x-slot name="icon">
                            <x-icons.camera class="h-7 w-7 text-white" />
                        </x-slot>
                    </x-button.secondary>
                </div>
                {{-- Uploader panel --}}
                <div x-show="open" @click.away="open = false" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                    class="absolute left-0 top-full z-50 mt-2 w-72 rounded-2xl border border-zinc-200 bg-white p-4 shadow-xl dark:border-zinc-700 dark:bg-zinc-900 sm:w-80"
                    x-cloak>
                    <livewire:utils.profile-picture-uploader />
                </div>
            </div>

            {{-- User Info --}}
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2">
                    <h1 class="text-2xl font-black tracking-tight text-zinc-900 dark:text-white sm:text-3xl">
                        {{ auth()->user()->name }}
                    </h1>
                    <span
                        class="rounded-full bg-red-100 px-3 py-0.5 text-xs font-semibold text-red-700 dark:bg-red-900/30 dark:text-red-400">
                        {{ auth()->user()->roles->pluck('name')->implode(', ') }}
                    </span>
                </div>

                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ auth()->user()->email }}</p>

                <div class="mt-3">
                    <livewire:handler.profile.bio-edit />
                </div>

                <div class="mt-3">
                    <livewire:inspire-component />
                </div>
            </div>
        </div>
    </div>

    {{-- Form Cards --}}
    <div class="grid gap-4 lg:grid-cols-2">

        {{-- Update Profile Info --}}
        <div class="rounded-xl border border-zinc-200 p-4 shadow-md dark:border-zinc-800 lg:p-6"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
            <header class="mb-6 border-b border-zinc-200 pb-5 dark:border-zinc-800">
                <h2 class="text-base font-bold text-zinc-900 dark:text-white">
                    {{ __('Informasi Profil') }}
                </h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Perbarui nama dan email akun Anda.') }}
                </p>
            </header>

            <form class="space-y-4" wire:submit="updateProfileInformation">
                {{-- Email --}}
                <div>
                    <x-input.label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300" for="email"
                        :value="__('Email')" />
                    <div class="flex items-center gap-2">
                        <x-input.text
                            class="block w-full rounded-xl border-0 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 ring-1 ring-zinc-200 placeholder:text-zinc-400 focus:ring-2 focus:ring-red-500 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700"
                            id="email" wire:model="email" type="email" autocomplete="email" />
                        @if (!is_null($user->email_verified_at))
                            <span
                                class="flex shrink-0 items-center gap-1 rounded-lg bg-green-100 px-2.5 py-1.5 text-xs font-semibold text-green-700 dark:bg-green-900/30 dark:text-green-400">
                                <x-icons.check class="h-3.5 w-3.5" />
                                Verified
                            </span>
                        @endif
                    </div>
                    <x-input.error class="mt-1.5" :messages="$errors->get('email')" />
                </div>

                {{-- Nama Lengkap --}}
                <div>
                    <x-input.label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300" for="name"
                        :value="__('Nama Lengkap')" />
                    <x-input.text
                        class="block w-full rounded-xl border-0 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 ring-1 ring-zinc-200 placeholder:text-zinc-400 focus:ring-2 focus:ring-red-500 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700"
                        id="name" wire:model="name" type="text" autocomplete="name" />
                    <x-input.error class="mt-1.5" :messages="$errors->get('name')" />
                </div>

                {{-- Save Button --}}
                <div class="flex items-center gap-4 pt-2">
                    <x-button.success type="submit">
                        <x-slot name="icon">
                            <x-icons.check class="h-4 w-4" />
                        </x-slot>
                        {{ __('Simpan Perubahan') }}
                    </x-button.success>
                </div>
            </form>
        </div>

        {{-- Update Password --}}
        <div class="rounded-xl border border-zinc-200 p-4 shadow-md dark:border-zinc-800 lg:p-6"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
            <header class="mb-6 border-b border-zinc-200 pb-5 dark:border-zinc-800">
                <h2 class="text-base font-bold text-zinc-900 dark:text-white">
                    {{ __('Ubah Kata Sandi') }}
                </h2>
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                    {{ __('Gunakan kata sandi yang panjang dan acak agar akun Anda tetap aman.') }}
                </p>
            </header>

            <form class="space-y-4" wire:submit="updatePassword">
                <div>
                    <x-input.label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300"
                        for="current_password" :value="__('Kata Sandi Saat Ini')" />
                    <x-input.text
                        class="mt-1 block w-full rounded-xl border-0 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 ring-1 ring-zinc-200 placeholder:text-zinc-400 focus:ring-2 focus:ring-red-500 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700"
                        id="current_password" wire:model="current_password" type="password" autocomplete="current-password" />
                    <x-input.error class="mt-1.5" :messages="$errors->get('current_password')" />
                </div>

                <div>
                    <x-input.label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300"
                        for="password" :value="__('Kata Sandi Baru')" />
                    <x-input.text
                        class="mt-1 block w-full rounded-xl border-0 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 ring-1 ring-zinc-200 placeholder:text-zinc-400 focus:ring-2 focus:ring-red-500 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700"
                        id="password" wire:model="password" type="password" autocomplete="new-password" />
                    <x-input.error class="mt-1.5" :messages="$errors->get('password')" />
                </div>

                <div>
                    <x-input.label class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300"
                        for="password_confirmation" :value="__('Konfirmasi Kata Sandi')" />
                    <x-input.text
                        class="mt-1 block w-full rounded-xl border-0 bg-zinc-50 px-4 py-2.5 text-sm text-zinc-900 ring-1 ring-zinc-200 placeholder:text-zinc-400 focus:ring-2 focus:ring-red-500 dark:bg-zinc-800/50 dark:text-white dark:ring-zinc-700"
                        id="password_confirmation" wire:model="password_confirmation" type="password" autocomplete="new-password" />
                    <x-input.error class="mt-1.5" :messages="$errors->get('password_confirmation')" />
                </div>

                <div class="flex items-center gap-4 pt-2">
                    <x-button.success type="submit">
                        <x-slot name="icon">
                            <x-icons.lock class="h-4 w-4" />
                        </x-slot>
                        {{ __('Perbarui Sandi') }}
                    </x-button.success>
                </div>
            </form>
        </div>
    </div>

    {{-- Advanced Authentication Cards: Passkeys & Device Session Manager --}}
    <div class="mt-4 grid gap-4 lg:grid-cols-2">
        <livewire:profile.passkey-manager />
        <livewire:profile.session-manager />
    </div>
</div>

