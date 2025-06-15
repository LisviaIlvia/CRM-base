<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class AliasServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $loader = AliasLoader::getInstance();

        $loader->alias('permission', \Spatie\Permission\Middleware\PermissionMiddleware::class);
        $loader->alias('role', \Spatie\Permission\Middleware\RoleMiddleware::class);
		$loader->alias('role_or_permission', \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
		
    }
}
