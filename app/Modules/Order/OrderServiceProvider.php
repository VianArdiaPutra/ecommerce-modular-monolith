<?php

namespace App\Modules\Order;

use App\Modules\Catalog\Events\ProductDeleted;
use App\Modules\Order\Listeners\RemoveDeletedProductFromCarts;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class OrderServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
        $this->loadRoutesFrom(__DIR__.'/Routes/web.php');
        $this->loadViewsFrom(__DIR__.'/Resources/views', 'order');

        Event::listen(ProductDeleted::class, RemoveDeletedProductFromCarts::class);
    }
}
