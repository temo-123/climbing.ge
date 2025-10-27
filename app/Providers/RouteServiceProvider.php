<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\RateLimiter;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * The path to the "home" route for your application.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureRateLimiting();

        parent::boot();
    }

    protected function configureRateLimiting()
    {
        // https://stackoverflow.com/questions/65406206/laravel-8-rate-limiter-not-working-for-routes
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(360)->by(optional($request->user())->id ?: $request->ip());
        });
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();

        //
    }

    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace($this->namespace)
            ->group(function () {
                require base_path('routes/api/auth.php');
                require base_path('routes/api/guide.php');
                require base_path('routes/api/shop.php');
                require base_path('routes/api/films.php');
                require base_path('routes/api/blog.php');
                require base_path('routes/api/user.php');
                require base_path('routes/api/meil.php');
                require base_path('routes/api/general.php');

                require base_path('routes/api/admin/blog.php');
                require base_path('routes/api/admin/films.php');
                require base_path('routes/api/admin/guide.php');
                require base_path('routes/api/admin/shop.php');
                require base_path('routes/api/admin/user.php');
            });
    }
}
