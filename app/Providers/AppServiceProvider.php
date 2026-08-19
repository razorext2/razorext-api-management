<?php

/** Goal: Mengatur service provider utama aplikasi termasuk registrasi dynamic components dan model events untuk WebSocket table refresh, Caller: bootstrap/app.php, Deps: ServiceProvider, Livewire, Models, TableRefreshed */

namespace App\Providers;

use App\Events\TableRefreshed;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if (file_exists(app_path('helpers.php'))) {
            require_once app_path('helpers.php');
        }

        URL::macro(
            'alternateHasCorrectSignature',
            function (Request $request, $absolute = true, array $ignoreQuery = []) {
                $ignoreQuery[] = 'signature';

                $absoluteUrl = url($request->path());
                $url = $absolute ? $absoluteUrl : '/'.$request->path();

                $queryString = collect(explode('&', (string) $request
                    ->server->get('QUERY_STRING')))
                    ->reject(fn ($parameter) => in_array(Str::before($parameter, '='), $ignoreQuery))
                    ->join('&');

                $original = rtrim($url.'?'.$queryString, '?');

                // Use the application key as the HMAC key
                $key = config('app.key'); // Ensure app.key is properly set in .env

                if (empty($key)) {
                    throw new \RuntimeException('Application key is not set.');
                }

                $signature = hash_hmac('sha256', $original, $key);

                return hash_equals($signature, (string) $request->query('signature', ''));
            }
        );

        URL::macro('alternateHasValidSignature', function (Request $request, $absolute = true, array $ignoreQuery = []) {
            return URL::alternateHasCorrectSignature($request, $absolute, $ignoreQuery)
                && URL::signatureHasNotExpired($request);
        });

        Request::macro('hasValidSignature', function ($absolute = true, array $ignoreQuery = []) {
            return URL::alternateHasValidSignature($this, $absolute, $ignoreQuery);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // force https
        URL::forceScheme('https');

        // Implicitly grant "Super Admin" role all permissions
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

        // force root url
        $this->app['url']->forceRootUrl($this->app['config']->get('app.url'));

        // Auto-register moved PowerGrid tables under their short/kebab/stud names
        if (is_dir(app_path('Livewire/PowergridTables'))) {
            $files = glob(app_path('Livewire/PowergridTables/*Table.php'));
            foreach ($files as $file) {
                $className = basename($file, '.php');
                $classPath = 'App\\Livewire\\PowergridTables\\'.$className;
                if (class_exists($classPath)) {
                    Livewire::component($className, $classPath);
                    $kebabName = Str::kebab($className);
                    Livewire::component($kebabName, $classPath);
                }
            }
        }

        // Register model observers for real-time PowerGrid table refreshes
        $modelToTablesMap = [
            'App\Models\ApiClient' => ['ApiClientTable'],
            'App\Models\User' => ['UserTable'],
            'App\Models\LogHistory' => ['LogTable'],
            'Spatie\Permission\Models\Role' => ['RolesTable'],
            'Spatie\Permission\Models\Permission' => ['PermissionsTable'],
        ];

        foreach ($modelToTablesMap as $modelClass => $tableNames) {
            if (class_exists($modelClass)) {
                $modelClass::saved(function ($model) use ($tableNames) {
                    foreach ($tableNames as $tableName) {
                        event(new TableRefreshed($tableName));
                    }
                });
                $modelClass::deleted(function ($model) use ($tableNames) {
                    foreach ($tableNames as $tableName) {
                        event(new TableRefreshed($tableName));
                    }
                });
            }
        }
    }
}
