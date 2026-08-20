<?php

/** Goal: Main Web Routing File, Caller: ServiceProvider */

use App\Http\Controllers\PushController;
use App\Livewire\Dashboard;
use App\Livewire\Handler\ApiClients\Show;
use App\Livewire\Handler\Permissions\Create;
use App\Livewire\Handler\Permissions\Index;
use App\Livewire\Handler\Permissions\Update;
use App\Livewire\Handler\Sandbox\AprioriSandbox;
use App\Livewire\Handler\User\Edit;
use App\Livewire\NotificationsIndex;
use App\Livewire\PowergridTables\LogTable;
use App\Livewire\ProfileEdit;
use App\Livewire\ProfileShow;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::middleware('throttle:high')->get('/', function () {
    return redirect('login');
})->name('landing.page');

Route::get('/ping', fn () => response()->json(['status' => 'pong', 'timestamp' => now()->timestamp]))->name('ping');

// Route requiring authentication
Route::middleware(['auth'])->group(function () {
    // Push Subscription
    Route::post('/push-subscribe', [PushController::class, 'subscribe']);

    // Notifications API/Read
    Route::get('notifications/{id}/mark-as-read', function ($id) {
        $notification = Auth::user()->unreadNotifications->find($id);
        if ($notification) {
            $notification->markAsRead();
        }

        return back()->with('success', 'Notification marked as read');
    })->name('notification.mark-as-read');
    Route::get('notifications/fetch', function () {
        $notifications = Auth::user()?->unreadNotifications()->take(10)->get();

        return response()->json([
            'success' => true,
            'message' => 'Data berhasil diambil',
            'data' => $notifications ?? [],
        ]);
    })->name('notification.fetch');

    // Dashboard group
    Route::prefix('dashboard')->as('')->group(function () {
        Route::livewire('/', Dashboard::class)->name('dashboard');

        // Profile (Livewire 4 Page Components)
        Route::livewire('me', ProfileShow::class)->name('profile.me');
        Route::livewire('profile', ProfileEdit::class)->name('profile.edit');

        // Notifications (Livewire 4 Page Components)
        Route::livewire('notifications', NotificationsIndex::class)->name('notifications.index');

        // Permissions
        Route::livewire('permissions', Index::class)->name('permissions.index')->middleware('permission:permissions-list');
        Route::livewire('permissions/create', Create::class)->name('permissions.create')->middleware('permission:permissions-create');
        Route::livewire('permissions/{permission}/edit', Update::class)->name('permissions.edit')->middleware('permission:permissions-edit');

        // Roles
        Route::livewire('roles', App\Livewire\Handler\Roles\Index::class)->name('roles.index')->middleware('permission:roles-list');
        Route::livewire('roles/create', App\Livewire\Handler\Roles\Create::class)->name('roles.create')->middleware('permission:roles-create');
        Route::livewire('roles/{role}/edit', App\Livewire\Handler\Roles\Update::class)->name('roles.edit')->middleware('permission:roles-edit');

        // Users
        Route::livewire('users', App\Livewire\Handler\User\Index::class)->name('users.index')->middleware('permission:users-list');
        Route::livewire('users/create', App\Livewire\Handler\User\Create::class)->name('users.create')->middleware('permission:users-create');
        Route::livewire('users/{user}/edit', Edit::class)->name('users.edit')->middleware('permission:users-edit');

        // API Clients Management
        Route::livewire('api-clients', App\Livewire\Handler\ApiClients\Index::class)->name('api-clients.index')->middleware('permission:api-clients-list');
        Route::livewire('api-clients/create', App\Livewire\Handler\ApiClients\Create::class)->name('api-clients.create')->middleware('permission:api-clients-create');
        Route::livewire('api-clients/{client}', Show::class)->name('api-clients.show')->middleware('permission:api-clients-list');
        Route::livewire('api-clients/{client}/edit', App\Livewire\Handler\ApiClients\Edit::class)->name('api-clients.edit')->middleware('permission:api-clients-edit');

        // Interactive Sandbox
        Route::get('sandbox', fn () => redirect()->route('sandbox.apriori'))->name('sandbox.index')->middleware('permission:sandbox-access');
        Route::livewire('sandbox/apriori', AprioriSandbox::class)->name('sandbox.apriori')->middleware('permission:sandbox-access');

        // Activity Logs
        Route::livewire('log', LogTable::class)->name('log.index')->middleware('permission:log-list');

        // Website Settings
        Route::livewire('settings', App\Livewire\Handler\Settings\Index::class)->name('settings.index')->middleware('permission:settings-manage');
    });
});

require __DIR__.'/auth.php';

// File streaming (protected: auth required + path sanitized)
Route::middleware('auth')->get('/file/{path}', function (string $path) {
    // Prevent path traversal: reject any '..' sequences
    abort_if(str_contains($path, '..'), 403);

    // Whitelist: only alphanumeric, slash, dash, underscore, dot
    abort_unless(preg_match('#^[\w/\-.]+$#', $path), 400);

    abort_unless(Storage::exists($path), 404);

    return Storage::response($path);
})->where('path', '.*')->name('file.show');
