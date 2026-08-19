<?php

namespace App\View\Components\Drawer;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DashboardMenu extends Component
{
    public array $links = [];

    public function __construct()
    {
        $this->links = $this->flatten(config('navigation'));
    }

    /**
     * Flatten the unified config into a single array for easier iteration
     */
    private function flatten(array $menu): array
    {
        $flat = [];

        foreach ($menu as $item) {
            if ($item['type'] === 'link') {
                $flat[] = [
                    'label' => $item['label'],
                    'mobile_label' => $item['mobile_label'] ?? $item['label'],
                    'link' => $item['route'],
                    'check' => is_array($item['check']) ? $item['check'][0] : $item['check'],
                    'icon' => $item['icon'],
                    'guard' => $item['guard'] ?? null,
                ];
            } elseif ($item['type'] === 'group') {
                foreach ($item['submenu'] as $sub) {
                    $perm = $sub['permission'] ?? null;

                    $guard = match (true) {
                        $perm === null => null,
                        is_array($perm) => ['any_permission', $perm],
                        default => ['can', $perm],
                    };

                    $flat[] = [
                        'label' => $sub['label'],
                        'mobile_label' => $sub['mobile_label'] ?? $sub['label'],
                        'link' => $sub['route'],
                        'check' => is_array($sub['check']) ? $sub['check'][0] : $sub['check'],
                        'icon' => $sub['icon'],
                        'guard' => $guard,
                    ];
                }
            }
        }

        return $flat;
    }

    public function render(): View|Closure|string
    {
        return view('components.drawer.dashboard-menu');
    }
}
