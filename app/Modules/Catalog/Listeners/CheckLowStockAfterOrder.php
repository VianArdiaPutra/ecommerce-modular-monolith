<?php

namespace App\Modules\Catalog\Listeners;

use App\Modules\Catalog\Models\Product;
use App\Modules\Order\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class CheckLowStockAfterOrder
{
    private const LOW_STOCK_THRESHOLD = 5;

    public function handle(OrderPlaced $event): void
    {
        foreach ($event->items as $item) {
            $product = Product::find($item['product_id']);

            if ($product && $product->stock <= self::LOW_STOCK_THRESHOLD) {
                Log::warning("Stok menipis: {$product->name} tersisa {$product->stock} (order #{$event->orderId}).");
            }
        }
    }
}
