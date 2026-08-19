<?php

namespace App\Livewire\Handler\Profile;

use App\Livewire\Concerns\HandlesErrors;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Validate;
use Livewire\Component;

class BioEdit extends Component
{
    use HandlesErrors;

    #[Validate('nullable|string|max:20')]
    public ?string $bio = null;

    public function mount(): void
    {
        $this->bio = auth()->user()->bio ?? '';
    }

    /**
     * Triggered on wire:model.blur — validate only, then persist.
     * Using .blur prevents N+1 DB writes per keystroke.
     */
    public function updatedBio(): void
    {
        $this->validateOnly('bio');

        $this->runSafely(function () {
            auth()->user()->update(['bio' => $this->bio]);
        }, 'Gagal memperbarui bio.', [
            'action' => 'update profile bio',
            'user_id' => auth()->id(),
        ]);
    }

    public function render(): View
    {
        return view('livewire.handler.profile.bio-edit');
    }
}
