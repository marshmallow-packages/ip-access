<?php

namespace Marshmallow\IpAccess;

use Marshmallow\IpAccess\Http\Middleware\IpWebAccess;
use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use Illuminate\Contracts\Http\Kernel;

class IpAccessServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/ip-access.php' => config_path('ip-access.php'),
        ], 'config');

        $kernel = $this->app->make(Kernel::class);
        $kernel->pushMiddleware(IpWebAccess::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/ip-access.php',
            'ip-access'
        );

        /**
         * Only run migrations if the config has use_nova
         * set to true.
         */
        if (config('ip-access.use_nova') === true) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }
}
