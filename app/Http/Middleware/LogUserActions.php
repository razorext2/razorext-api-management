<?php

/** Goal: Log user activity details safely handling cases where REMOTE_ADDR is missing, Caller: bootstrap/app.php, Deps: DB, Auth, Log, Request */

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LogUserActions
{
    /**
     * Jalur URL yang akan sepenuhnya diabaikan dari pencatatan log.
     */
    protected array $ignoredPaths = [
        'livewire/*',
        'broadcasting/auth',
        'push-subscribe',
        'ping',
        'notifications/fetch',
        'file/*',
    ];

    /**
     * Segmen entitas (prefix rute) yang akan diabaikan.
     */
    protected array $ignoredEntities = [
        'livewire',
        'telescope',
        'horizon',
        'laravelpwa',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Simpan response untuk digunakan nanti
        $response = $next($request);

        // Hanya catat log jika user login dan request layak dicatat
        if (Auth::check() && $this->shouldLog($request)) {
            $user = Auth::user();
            $action = $this->getActionName($request);
            $entity = $this->getEntityName($request);

            $this->saveLog($user, $entity, $action, $request);
        }

        return $response;
    }

    /**
     * Menentukan apakah request ini layak dicatat ke dalam log.
     */
    protected function shouldLog(Request $request): bool
    {
        // 1. Abaikan jalur yang ada di blacklist
        foreach ($this->ignoredPaths as $path) {
            if ($request->is($path)) {
                return false;
            }
        }

        // 2. Abaikan entitas tertentu (seperti rute internal livewire)
        $entity = $this->getEntityName($request);
        if (in_array($entity, $this->ignoredEntities) || str_starts_with($entity, 'generated::')) {
            return false;
        }

        // 3. Hanya catat metode yang umum
        return $request->isMethod('get') ||
               $request->isMethod('post') ||
               $request->isMethod('put') ||
               $request->isMethod('patch') ||
               $request->isMethod('delete');
    }

    /**
     * Menentukan nama aksi berdasarkan metode HTTP dan nama rute.
     */
    protected function getActionName(Request $request): string
    {
        $method = strtolower($request->method());
        $route = $request->route();
        $routeName = $route ? $route->getName() : null;

        if ($method === 'get') {
            if ($routeName && str_contains($routeName, '.')) {
                $parts = explode('.', $routeName);
                $subAction = end($parts);

                // Mapping rute resource ke nama yang lebih ramah
                return match ($subAction) {
                    'index' => 'list',
                    'create' => 'form_create',
                    'edit' => 'form_edit',
                    'show' => 'details',
                    default => $subAction,
                };
            }

            return 'access';
        }

        return match ($method) {
            'post' => 'create',
            'put', 'patch' => 'update',
            'delete' => 'delete',
            default => 'unknown',
        };
    }

    /**
     * Menentukan nama entitas (modul) berdasarkan nama rute atau segment URL.
     */
    protected function getEntityName(Request $request): string
    {
        $route = $request->route();

        if ($route) {
            $routeName = $route->getName();
            if ($routeName) {
                $parts = explode('.', $routeName);

                // Jika rute seperti 'admin.pegawai.index', ambil 'admin.pegawai'
                if (count($parts) > 1) {
                    array_pop($parts); // Hapus bagian terakhir (index, create, dsb)

                    return implode('.', $parts);
                }

                return $parts[0];
            }
        }

        return $request->segment(1) ?? 'unknown';
    }

    /**
     * Menyimpan log ke database dan file log Laravel.
     */
    protected function saveLog($user, string $entity, string $action, Request $request): void
    {
        $actionDescription = "{$entity} > {$action}";

        // Format IP yang detail sesuai permintaan
        $remoteAddr = $_SERVER['REMOTE_ADDR'] ?? $request->ip() ?? '127.0.0.1';
        $ipInfo = "[
                        LaravelIP: {$request->ip()},
                        X-Forwarded-For: {$request->header('X-Forwarded-For')},
                        X-Real-IP: {$request->header('X-Real-IP')},
                        Remote-Addr: {$remoteAddr},
                        Path: {$request->path()},
                    ]";

        $data = [
            'user_id' => $user->id,
            'user_action' => $actionDescription,
            'ip_address' => $ipInfo,
            'user_agent' => $request->header('User-Agent'),
            'user_location' => 'Unknown',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Simpan ke Database (log_histories)
        try {
            DB::table('log_histories')->insert($data);
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan log user ke DB: '.$e->getMessage());
        }

        // Simpan ke laravel.log (untuk debugging/audit sekunder)
        Log::info("User Activity: {$actionDescription}", [
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'path' => $request->path(),
        ]);
    }
}
