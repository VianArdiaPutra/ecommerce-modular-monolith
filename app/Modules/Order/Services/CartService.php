<?php

namespace App\Modules\Order\Services;

use App\Modules\Catalog\Contracts\ProductServiceInterface;
use App\Modules\Catalog\Contracts\ProductSnapshot;
use App\Modules\Catalog\Exceptions\InsufficientStockException;
use App\Modules\Order\Models\Cart;

class CartService
{
    public function __construct(private ProductServiceInterface $products) {}

    public function getOrCreateCart(int $customerId): Cart
    {
        return Cart::firstOrCreate(['customer_id' => $customerId]);
    }

    public function addItem(int $customerId, int $productId, int $quantity): void
    {
        $snapshot = $this->products->getSnapshot($productId);
        $cart = $this->getOrCreateCart($customerId);
        $item = $cart->items()->where('product_id', $productId)->first();

        $newQuantity = ($item?->quantity ?? 0) + $quantity;
        $this->assertAvailable($snapshot, $newQuantity);

        if ($item) {
            $item->update(['quantity' => $newQuantity]);
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $newQuantity,
            ]);
        }
    }

    public function updateQuantity(int $customerId, int $productId, int $quantity): void
    {
        if ($quantity <= 0) {
            $this->removeItem($customerId, $productId);

            return;
        }

        $snapshot = $this->products->getSnapshot($productId);
        $this->assertAvailable($snapshot, $quantity);

        $this->getOrCreateCart($customerId)
            ->items()
            ->where('product_id', $productId)
            ->update(['quantity' => $quantity]);
    }

    public function removeItem(int $customerId, int $productId): void
    {
        $this->getOrCreateCart($customerId)
            ->items()
            ->where('product_id', $productId)
            ->delete();
    }

    /**
     * @return array{items: list<array<string, mixed>>, total: float}
     */
    public function getCartDetails(int $customerId): array
    {
        $cart = $this->getOrCreateCart($customerId)->load('items');
        $lines = [];
        $total = 0.0;

        foreach ($cart->items as $item) {
            $snapshot = $this->products->getSnapshot($item->product_id);
            $subtotal = $snapshot->price * $item->quantity;
            $total += $subtotal;

            $lines[] = [
                'product_id' => $item->product_id,
                'name' => $snapshot->name,
                'price' => $snapshot->price,
                'quantity' => $item->quantity,
                'subtotal' => $subtotal,
            ];
        }

        return ['items' => $lines, 'total' => $total];
    }

    private function assertAvailable(ProductSnapshot $snapshot, int $quantity): void
    {
        if (! $snapshot->isActive || $snapshot->stock < $quantity) {
            throw InsufficientStockException::forProduct(
                $snapshot->name,
                $snapshot->stock,
                $quantity,
            );
        }
    }
}
