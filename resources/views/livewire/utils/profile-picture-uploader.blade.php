<form wire:submit="save">
    <div class="mt-4 flex flex-col gap-2" x-data="{ uploading: false, progress: 0 }" x-on:livewire-upload-start="uploading = true"
        x-on:livewire-upload-finish="uploading = false" x-on:livewire-upload-cancel="uploading = false"
        x-on:livewire-upload-error="uploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">

        <div class="flex items-center gap-x-2">
            <label class="block text-sm font-medium text-gray-900 dark:text-white" for="user_avatar">Change </label>

            <div x-show="uploading" class="w-full rounded-full bg-gray-200 dark:bg-gray-700">
                <div class="rounded-full bg-blue-600 py-1 text-center text-xs font-medium leading-none text-blue-100"
                    x-bind:style="{ width: progress + '%' }"> </div>
            </div>
        </div>

        @if ($photo)
            <div>
                <img class="h-20 w-20 rounded-lg object-cover" src="{{ $photo->temporaryUrl() }}">
            </div>
        @endif

        <div class="flex items-center gap-x-2">
            <input
                class="w-full cursor-pointer rounded-lg border border-zinc-200 bg-gray-50 text-sm text-gray-900 focus:outline-none dark:border-zinc-800 dark:bg-gray-700 dark:text-gray-400 dark:placeholder-gray-400"
                aria-describedby="user_avatar_help" id="user_avatar" wire:model="photo" type="file"
                accept="image/png, image/jpeg, image/jpg, image/gif, image/webp">
            <x-button.primary type="submit" wire:loading.attr="disabled" wire:target="photo, save">
                <span wire:loading wire:target="photo">Uploading...</span>
                <span wire:loading wire:target="save">Menyimpan...</span>
                <span wire:loading.remove wire:target="photo, save">Simpan</span>
            </x-button.primary>
        </div>

        <span class="mt-1 text-xs text-gray-500 dark:text-gray-400" id="user_avatar_help">Format gambar (JPG, PNG, WebP,
            GIF) maksimal 2MB</span>

        @error('photo')
            <span class="error text-red-500">{{ $message }}</span>
        @enderror
    </div>
</form>
