{{-- Goal: Create permissions form page, Livewire: Handler\Permissions\Create --}}
<div class="w-full space-y-4">
    <!-- Top Header Navigation -->
    <div class="rounded-xl border border-zinc-200 p-6 shadow-2xl transition-all duration-500 ease-in-out dark:border-zinc-800"
        x-bind:class="dynamicBg ?
            'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
            'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">
        <div class="flex items-center gap-4">
            <x-button.danger id="back-btn" wire:navigate href="{{ route('permissions.index') }}">
                <x-icons.angle-left class="h-5 w-5" />
            </x-button.danger>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-gray-800 dark:text-white">
                    Tambah Data Perizinan
                </h2>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    Tambah satu atau beberapa perizinan baru sekaligus.
                </p>
            </div>
        </div>
    </div>

    <!-- Main Content Form -->
    <form wire:submit.prevent="save" class="space-y-4">
        @csrf
        <div class="group relative overflow-hidden rounded-xl border border-zinc-200 p-8 dark:border-zinc-800"
            x-bind:class="dynamicBg ?
                'bg-glass-light dark:bg-glass-dark border-glass-border-light dark:border-glass-border-dark backdrop-blur-md shadow-sm' :
                'bg-white dark:bg-dark-primary border-zinc-200 dark:border-zinc-800 shadow-sm'">

            <div class="mb-8 flex items-center gap-3">
                <div class="h-10 w-1 rounded-full bg-blue-600"></div>
                <h3 class="text-xl font-bold text-gray-800 dark:text-white">Daftar Perizinan Baru</h3>
            </div>

            <div class="w-full">
                <div class="flex flex-col gap-2">
                    @foreach ($form->name as $index => $permission)
                        <div class="flex flex-row items-center gap-2">
                            <div class="flex-1">
                                <x-input.basic id="name.{{ $index }}" name="name[]"
                                    placeholder="Isi dengan nama perizinan (cth: user-create)" wire:model.blur="form.name.{{ $index }}" />
                            </div>

                            <x-button.danger class="h-fit w-fit" wire:click="removeField({{ $index }})"
                                wire:loading.attr="disabled" wire:target="removeField({{ $index }})">
                                <x-slot name="icon">
                                    <x-icons.trash-bin wire:loading.remove wire:target="removeField({{ $index }})"
                                        class="icon h-5 w-5" />
                                    <x-icons.loading wire:loading wire:target="removeField({{ $index }})"
                                        class="h-4 w-4 animate-spin" />
                                </x-slot>
                            </x-button.danger>
                        </div>
                        @error('form.name.' . $index)
                            <span class="error text-sm text-red-500">{{ $message }}</span>
                        @enderror
                    @endforeach
                </div>
            </div>
        </div>

        <div class="flex items-center gap-3">
            <x-button.primary wire:click="addField" type="button" wire:loading.attr="disabled" wire:target="addField">
                <x-slot name="icon">
                    <x-icons.plus wire:loading.remove wire:target="addField" class="icon h-5 w-5" />
                    <x-icons.loading wire:loading wire:target="addField" class="h-4 w-4 animate-spin" />
                </x-slot>

                <span wire:loading.remove wire:target="addField">Tambah bidang lagi</span>
                <span wire:loading wire:target="addField">Memproses...</span>
            </x-button.primary>

            <x-button.primary id="store" type="submit" wire:loading.attr="disabled" wire:target="save">
                <x-slot name="icon">
                    <x-icons.angle-right wire:loading.remove wire:target="save" class="icon h-5 w-5" />
                    <x-icons.loading wire:loading wire:target="save" class="h-4 w-4 animate-spin" />
                </x-slot>

                <span wire:loading.remove wire:target="save">Submit</span>
                <span wire:loading wire:target="save">Memproses...</span>
            </x-button.primary>

            <x-button.secondary href="{{ route('permissions.index') }}" wire:navigate>
                Batal
            </x-button.secondary>
        </div>
    </form>
</div>
