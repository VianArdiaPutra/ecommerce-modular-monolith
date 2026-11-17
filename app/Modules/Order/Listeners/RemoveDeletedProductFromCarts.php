<?php

namespace App\Modules\Order\Listeners;

use App\Modules\Catalog\Events\ProductDeleted;
use App\Modules\Order\Models\CartItem;

class RemoveDeletedProductFromCarts
{
    public function handle(ProductDeleted $event): void
    {
        CartItem::where('product_id', $event->productId)->delete();
    }
}
