<?php

namespace App\Modules\Catalog\Events;

use Illuminate\Foundation\Events\Dispatchable;

class ProductDeleted
{
    use Dispatchable;

    public function __construct(public readonly int $productId) {}
}
