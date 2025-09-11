<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/dashboard';


    /**
     *
     */
    protected $apiNamespace = 'App\\Http\\Controllers\\API';
    protected $misNamespace = 'App\\Http\\Controllers\\MIS';
    protected $webNamespace = 'App\\Http\\Controllers\\Frontend';
    protected $grvNamespace = 'App\\Http\\Controllers\\Grievance';


    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->namespace($this->apiNamespace)
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                // ->namespace($this->misNamespace)
                ->group(base_path('routes/mis.php'));

            Route::middleware('web')
                ->namespace($this->grvNamespace)
                ->group(base_path('routes/grievance.php'));

            Route::middleware('web')
                ->group(base_path('routes/auth.php'));

                Route::middleware('web')
                    ->namespace($this->webNamespace)
                    ->group(base_path('routes/web.php'));
        });
    }
}
