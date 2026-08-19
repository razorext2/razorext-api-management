<?php

namespace App\Livewire\Components;

use App\Models\LogHistory;
use App\Models\User;
use Livewire\Component;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class Card extends Component
{
    public string $type = 'dashboard';

    public array $cards = [];

    public function render()
    {
        $datas = $this->resolveCards();

        $totalData = collect($datas)->filter(function ($card) {
            $permission = $card['permission'] ?? 'all';

            return $permission === 'all' || auth()->user()->hasPermissionTo($permission);
        })->count();

        return view('livewire.components.card', ['data' => $datas, 'totalData' => $totalData]);
    }

    protected function resolveCards(): array
    {
        if (! empty($this->cards)) {
            return $this->cards;
        }

        return match ($this->type) {
            'dashboard' => $this->getDashboardCards(),
            default => [],
        };
    }

    protected function getDashboardCards(): array
    {
        return [
            [
                'permission' => 'users-list',
                'label' => 'Total Users',
                'count' => User::count(),
                'indicator' => 'Pengguna',
                'icon' => 'icons.users',
                'color' => 'red',
            ],
            [
                'permission' => 'roles-list',
                'label' => 'Total Roles',
                'count' => Role::count(),
                'indicator' => 'Peran',
                'icon' => 'icons.check',
                'color' => 'red',
            ],
            [
                'permission' => 'permissions-list',
                'label' => 'Total Permissions',
                'count' => Permission::count(),
                'indicator' => 'Hak Akses',
                'icon' => 'icons.wallet',
                'color' => 'red',
            ],
            [
                'permission' => 'log-list',
                'label' => 'Log Aktivitas',
                'count' => LogHistory::count(),
                'indicator' => 'Catatan',
                'icon' => 'icons.cash-register',
                'color' => 'red',
            ],
        ];
    }
}
