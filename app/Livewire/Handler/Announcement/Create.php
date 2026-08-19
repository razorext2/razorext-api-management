<?php

/** Goal: Create form page for new announcement, Caller: routes/web.php (announcement.create), Deps: Announcement, Role, User, HandlesErrors */

namespace App\Livewire\Handler\Announcement;

use App\Enums\AnnouncementStatus;
use App\Livewire\Concerns\HandlesErrors;
use App\Models\Announcement;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;

class Create extends Component
{
    use HandlesErrors, WithFileUploads;

    public $title;

    public $description;

    public $target_type = 'all';

    public $target_roles = [];

    public $target_users = [];

    public $file;

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
                'status' => AnnouncementStatus::Active->value,
            ];

            if ($this->file) {
                $data['file_path'] = $this->file->store('announcements', 'public');
            }

            Announcement::create($data);

            session()->flash('status', 'Pengumuman berhasil ditambahkan');
            $this->redirect(route('announcement.index'), navigate: true);
        }, 'Gagal menambahkan pengumuman.', [
            'user_id' => auth()->id(),
            'action' => 'create announcement',
        ]);
    }

    public function render(): View
    {
        return view('livewire.handler.announcement.create', [
            'roles' => Role::select(['id', 'name'])->get(),
            'users' => User::select(['id', 'name', 'email'])
                ->where('is_active', true)
                ->limit(200)
                ->get(),
        ]);
    }
}
