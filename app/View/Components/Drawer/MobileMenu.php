<?php

namespace App\View\Components\Drawer;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class MobileMenu extends Component
{
    public array $drawerLinks = [];

    public function __construct()
    {
        $this->drawerLinks = $this->flatten(config('navigation'));
    }

    /**
     * Flatten the desktop menu tree (links + groups) into a single-level
     * array suitable for the mobile drawer grid.
     *
     * - type='link'  → included as-is (guard kept)
     * - type='group' → each submenu item is expanded into its own entry;
     *                  the submenu's 'permission' is converted to the
     *                  unified guard format so the blade can handle it
     *                  with the same match() pattern as the sidebar.
     */
    private function flatten(array $menu): array
    {
        $flat = [];

        foreach ($menu as $item) {
            if ($item['type'] === 'link') {
                $flat[] = [
                    'label' => $item['label'],
                    'link' => $item['route'],
                    'check' => is_array($item['check']) ? $item['check'][0] : $item['check'],
                    'icon' => $item['icon'],
                    'guard' => $item['guard'] ?? null,
                ];
            } elseif ($item['type'] === 'group') {
                foreach ($item['submenu'] as $sub) {
                    $perm = $sub['permission'] ?? null;

                    // Convert submenu permission to the unified guard format
                    $guard = match (true) {
                        $perm === null => null,
                        is_array($perm) => ['any_permission', $perm],
                        default => ['can', $perm],
                    };

                    $flat[] = [
                        'label' => $sub['mobile_label'],
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
        return view('components.drawer.mobile-menu');
    }
}
