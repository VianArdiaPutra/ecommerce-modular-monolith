<?php

namespace App\Modules\Catalog;

use App\Modules\Catalog\Contracts\ProductServiceInterface;
use App\Modules\Catalog\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class CatalogServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ProductServiceInterface::class, ProductService::class);
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        $this->loadRoutesFrom(__DIR__ . '/Routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/Resources/views', 'catalog');
    }
}
