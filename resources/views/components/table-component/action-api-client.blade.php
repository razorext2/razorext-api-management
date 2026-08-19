{{-- Action buttons for ApiClient PowerGrid table --}}
<div class="flex items-center justify-center gap-2">
    <x-button.primary href="{{ route('api-clients.edit', $row->id) }}" wire:navigate class="text-xs py-1.5 px-3">
        Edit
    </x-button.primary>
    @can('api-clients-delete')
        <x-button.danger
            x-data
            @click="Swal.fire({
                title: 'Hapus API Client?',
                text: 'Data client {{ addslashes($row->name) }} beserta kunci aksesnya akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.deleteClient({{ $row->id }});
                }
            })"
            :iconOnly="true"
            class="text-xs py-1.5 px-2"
            title="Hapus API Client">
            <x-slot name='icon'>
                <x-icons.trash-bin class='h-4 w-4' />
            </x-slot>
        </x-button.danger>
    @endcan
</div>
