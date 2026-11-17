<?php

use App\Modules\Catalog\CatalogServiceProvider;
use App\Modules\Customer\CustomerServiceProvider;
use App\Modules\Order\OrderServiceProvider;
use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    CatalogServiceProvider::class,
    CustomerServiceProvider::class,
    OrderServiceProvider::class,
];
