<?php

/** Goal: Edit form page for existing announcement, Caller: routes/web.php (announcement.edit), Deps: Announcement, Role, User, HandlesErrors */

namespace App\Livewire\Handler\Announcement;

use App\Livewire\Concerns\HandlesErrors;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class Edit extends Component
{
    use HandlesErrors, WithFileUploads;

    public Announcement $announcement;

    public $title;

    public $description;

    public $target_type = 'all';

    public $target_roles = [];

    public $target_users = [];

    public $file;

    public $existing_file;

    public function mount(Announcement $announcement): void
    {
        $this->announcement = $announcement;
        $this->title = $announcement->title;
        $this->description = $announcement->description;
        $this->target_type = $announcement->target_type;
        $this->target_roles = $announcement->target_roles ?? [];
        $this->target_users = $announcement->target_users ?? [];
        $this->existing_file = $announcement->file_path;
    }

    protected function rules(): array
    {
        $rules = [
            'title' => 'required|string|max:128',
            'description' => 'required|string',
            'target_type' => 'required|in:all,role,user',
            'file' => 'nullable|mimes:pdf|max:2048',
        ];

        if ($this->target_type === 'role') {
            $rules['target_roles'] = 'required|array|min:1';
        } elseif ($this->target_type === 'user') {
            $rules['target_users'] = 'required|array|min:1';
        }

        return $rules;
    }

    public function save(): void
    {
        $this->validate();

        $this->runSafely(function () {
            $data = [
                'title' => $this->title,
                'description' => $this->description,
                'target_type' => $this->target_type,
                'target_roles' => $this->target_type === 'role' ? $this->target_roles : null,
                'target_users' => $this->target_type === 'user' ? $this->target_users : null,
            ];

            if ($this->file) {
                if ($this->existing_file) {
                    Storage::disk('public')->delete($this->existing_file);
                }
                $data['file_path'] = $this->file->store('announcements', 'public');
            }

            $this->announcement->update($data);

            session()->flash('status', 'Pengumuman berhasil diperbarui');
            $this->redirect(route('announcement.index'), navigate: true);
        }, 'Gagal memperbarui pengumuman.', [
            'announcement_id' => $this->announcement->id,
            'user_id' => auth()->id(),
            'action' => 'update announcement',
        ]);
    }

    public function render(): View
    {
        return view('livewire.handler.announcement.edit', [
            'roles' => Role::select(['id', 'name'])->get(),
            'users' => User::select(['id', 'name', 'email'])
                ->where('is_active', true)
                ->limit(200)
                ->get(),
        ]);
    }
}
