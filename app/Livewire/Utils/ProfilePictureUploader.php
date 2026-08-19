<?php

namespace App\Livewire\Utils;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class ProfilePictureUploader extends Component
{
    use WithFileUploads;

    #[Validate('required|image|mimes:jpeg,png,jpg,gif,webp|max:2048', message: [
        'photo.required' => 'Pilih foto terlebih dahulu.',
        'photo.image' => 'File harus berupa gambar.',
        'photo.mimes' => 'Format foto harus JPG, JPEG, PNG, GIF, atau WebP.',
        'photo.max' => 'Ukuran foto maksimal 2MB.',
    ])]
    public $photo;

    public function save()
    {
        $this->validate();

        $filename = 'avatar-'.Auth::id().'.'.$this->photo->extension();
        $this->photo->storeAs('public/profile-pictures', $filename);

        User::where('id', Auth::id())->update([
            'profile_pic' => $filename,
        ]);

        $this->dispatch('swal',
            title: 'Foto Profil Diperbarui',
            text: 'Foto profil Anda berhasil diperbarui.',
            icon: 'success'
        );

        $this->redirect(route('profile.edit'));
    }

    public function render()
    {
        return view('livewire.utils.profile-picture-uploader');
    }
}
