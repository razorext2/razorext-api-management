<form wire:submit.prevent="delete">
    @csrf
    @method('delete')

    <x-button.danger id="delete" type="submit">
        <x-icons.trash-bin class="h-5 w-5" />
    </x-button.danger>
</form>
