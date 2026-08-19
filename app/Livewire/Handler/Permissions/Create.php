<?php

namespace App\Livewire\Handler\Permissions;

use App\Livewire\Concerns\HandlesErrors;
use App\Livewire\Forms\Permissions\Post;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Create extends Component
{
    use HandlesErrors;

    public Post $form;

    public function addField(): void
    {
        $this->form->name[] = '';
    }

    public function removeField(int $index): void
    {
        if (count($this->form->name) > 1) {
            unset($this->form->name[$index]);
            $this->form->name = array_values($this->form->name);
        }
    }

    public function save(): void
    {
        // Pindahkan validasi ke luar agar jika invalid, pesan merah form muncul secara native,
        // bukan ditangkap sebagai pesan error Exception Swal.
        $this->form->validate();

        $this->runSafely(function () {
            // define $data = array name
            $data = $this->form->name;

            // cek jika value tiap array itu berbeda
            if (count(array_unique($data)) !== count($data)) {
                $this->dispatch('swal', title: 'Gagal', text: 'Tidak boleh ada nama yang sama', icon: 'error');

                return;
            }

            // panggil method store di form Post
            $this->form->store();

            // panggil event swal, tampilkan pesan
            $this->dispatch('swal', title: 'Berhasil', text: 'Berhasil menambah data perizinan', icon: 'success');

            // redirect
            $this->redirect(route('permissions.index'), navigate: true);
        }, 'Gagal menyimpan data hak akses/perizinan.', [
            'action' => 'create permissions',
            'user_id' => auth()->id(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.handler.permissions.create');
    }
}
