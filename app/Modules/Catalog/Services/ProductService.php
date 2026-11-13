<?php

namespace App\Modules\Catalog\Services;

use App\Modules\Catalog\Contracts\ProductServiceInterface;
use App\Modules\Catalog\Contracts\ProductSnapshot;
use App\Modules\Catalog\Exceptions\InsufficientStockException;
use App\Modules\Catalog\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductService implements ProductServiceInterface
{
    public function getSnapshot(int $productId): ProductSnapshot
    {
        $product = Product::findOrFail($productId);

        return new ProductSnapshot(
            id: $product->id,
            name: $product->name,
            price: (float) $product->price,
            stock: $product->stock,
            isActive: $product->is_active,
        );
    }

    public function hasStock(int $productId, int $quantity): bool
    {
        return Product::whereKey($productId)
            ->where('is_active', true)
            ->where('stock', '>=', $quantity)
            ->exists();
    }

    public function decreaseStock(int $productId, int $quantity): void
    {
        DB::transaction(function () use ($productId, $quantity) {
            $product = Product::whereKey($productId)->lockForUpdate()->firstOrFail();

            if (! $product->is_active || $product->stock < $quantity) {
                throw InsufficientStockException::forProduct(
                    $product->name,
                    $product->stock,
                    $quantity,
                );
            }

            $product->decrement('stock', $quantity);
        });
    }
}
