<?php

namespace App\Livewire\Profile;

use App\Livewire\Concerns\HandlesErrors;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Livewire\Component;

class SessionManager extends Component
{
    use HandlesErrors;

    public string $password = '';

    public bool $showConfirmLogoutModal = false;

    public function openConfirmLogoutModal(): void
    {
        $this->reset(['password']);
        $this->resetValidation();
        $this->showConfirmLogoutModal = true;
    }

    public function logoutOtherSessions(): void
    {
        $this->validate([
            'password' => 'required|current_password',
        ]);

        $this->runSafely(function () {
            $user = auth()->user();
            if (! $user) {
                return;
            }

            DB::table('sessions')
                ->where('user_id', $user->id)
                ->where('id', '!=', session()->getId())
                ->delete();

            $this->showConfirmLogoutModal = false;
            $this->reset(['password']);

            $this->dispatch('swal', title: 'Berhasil!', text: 'Semua sesi lain telah berhasil dikeluarkan.', icon: 'success');
        }, 'Gagal mengeluarkan sesi lain.', [
            'user_id' => auth()->id(),
            'action' => 'logout other browser sessions',
        ]);
    }

    public function revokeSession(string $sessionId): void
    {
        $this->runSafely(function () use ($sessionId) {
            DB::table('sessions')
                ->where('user_id', auth()->id())
                ->where('id', $sessionId)
                ->delete();

            $this->dispatch('swal', title: 'Berhasil!', text: 'Sesi perangkat telah dicabut.', icon: 'success');
        }, 'Gagal mencabut sesi.', [
            'user_id' => auth()->id(),
            'session_id' => $sessionId,
            'action' => 'revoke session',
        ]);
    }

    public function getSessionsProperty(): array
    {
        if (config('session.driver') !== 'database') {
            return [];
        }

        $currentSessionId = session()->getId();

        return DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) use ($currentSessionId) {
                $agent = $this->createAgent($session->user_agent);

                return (object) [
                    'id' => $session->id,
                    'agent' => [
                        'is_desktop' => $agent->isDesktop(),
                        'platform' => $agent->platform() ?: 'Unknown OS',
                        'browser' => $agent->browser() ?: 'Unknown Browser',
                    ],
                    'ip_address' => $session->ip_address,
                    'is_current_device' => $session->id === $currentSessionId,
                    'last_active' => Carbon::createFromTimestamp($session->last_activity)->locale('id')->diffForHumans(),
                ];
            })
            ->toArray();
    }

    protected function createAgent(?string $userAgent): Agent
    {
        $agent = new Agent;
        if ($userAgent) {
            $agent->setUserAgent($userAgent);
        }

        return $agent;
    }

    public function render(): View
    {
        return view('livewire.profile.session-manager', [
            'sessions' => $this->sessions,
        ]);
    }
}
