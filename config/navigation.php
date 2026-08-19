<?php

return [
    // ─── Dashboard ────────────────────────────────────────────────────────────
    [
        'type' => 'link',
        'label' => 'Dashboard',
        'route' => 'dashboard',
        'check' => ['dashboard'],
        'icon' => 'home',
        'guard' => null,
        'navigate' => true,
    ],

    // ─── Settings ─────────────────────────────────────────────────────────────
    [
        'type' => 'header',
        'label' => 'Settings',
    ],

    // ─── User Settings ────────────────────────────────────────────────────────
    [
        'type' => 'group',
        'label' => 'User Settings',
        'icon' => 'user-setting',
        'guard' => ['any_permission', ['users-list', 'roles-list', 'permissions-list']],
        'submenu' => [
            [
                'label' => 'Users',
                'mobile_label' => 'Manajemen User',
                'route' => 'users.index',
                'check' => ['users.*'],
                'icon' => 'profile-card',
                'permission' => 'users-list',
                'navigate' => true,
            ],
            [
                'label' => 'Roles',
                'mobile_label' => 'Manajemen Role',
                'route' => 'roles.index',
                'check' => ['roles.*'],
                'icon' => 'badge-check',
                'permission' => 'roles-list',
                'navigate' => true,
            ],
            [
                'label' => 'Permissions',
                'mobile_label' => 'Manajemen Permission',
                'route' => 'permissions.index',
                'check' => ['permissions.*'],
                'icon' => 'adjustment',
                'permission' => 'permissions-list',
                'navigate' => true,
            ],
        ],
    ],

    // ─── System Settings ──────────────────────────────────────────────────────
    [
        'type' => 'group',
        'label' => 'System Settings',
        'icon' => 'computer',
        'guard' => ['any_permission', ['log-list', 'settings-manage']],
        'submenu' => [
            [
                'label' => 'Log Aktivitas',
                'mobile_label' => 'Log Aktivitas',
                'route' => 'log.index',
                'check' => ['log.*'],
                'icon' => 'window',
                'permission' => 'log-list',
                'navigate' => true,
            ],
            [
                'label' => 'Pengaturan Website',
                'mobile_label' => 'Pengaturan Website',
                'route' => 'settings.index',
                'check' => ['settings.*'],
                'icon' => 'adjustment',
                'permission' => 'settings-manage',
                'navigate' => true,
            ],
        ],
    ],
];
