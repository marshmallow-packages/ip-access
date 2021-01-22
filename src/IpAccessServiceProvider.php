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
}
