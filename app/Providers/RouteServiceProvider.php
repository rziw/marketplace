<?php

namespace App\Providers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Route::bind('shop', function ($id) {

            $shop = Shop::where('id', $id);

            if (request()->route()->hasParameter('product')) {
                $shop->whereHas('products', function ($q) {
                    $q->where('products.id', request()->route('product'));
                });
            }

            return $shop->firstOrFail();
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
             ->group(base_path('routes/api/api.php'));

        Route::prefix('api')
            ->middleware(['auth.role:admin,customer,seller', 'api'])
            ->namespace($this->namespace)
            ->group(base_path('routes/api/user.php'));

        Route::prefix('api/seller')
            ->middleware(['auth.role:seller', 'api'])
            ->namespace($this->namespace.'\Seller')
            ->group(base_path('routes/api/seller.php'));

        Route::prefix('api/admin')
            ->middleware(['auth.role:admin', 'api'])
            ->namespace($this->namespace.'\Admin')
            ->group(base_path('routes/api/admin.php'));
    }
}
