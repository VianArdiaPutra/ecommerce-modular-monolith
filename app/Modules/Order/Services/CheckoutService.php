<?php

namespace App\Modules\Order\Services;

use App\Modules\Catalog\Contracts\ProductServiceInterface;
use App\Modules\Customer\Contracts\CustomerServiceInterface;
use App\Modules\Order\Events\OrderPlaced;
use App\Modules\Order\Exceptions\EmptyCartException;
use App\Modules\Order\Models\Cart;
use App\Modules\Order\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    public function __construct(
        private ProductServiceInterface $products,
        private CustomerServiceInterface $customers,
    ) {}

    public function checkout(int $customerId, int $addressId): Order
    {
        $cart = Cart::with('items')->where('customer_id', $customerId)->first();

        if (! $cart || $cart->items->isEmpty()) {
            throw new EmptyCartException;
        }

        $address = $this->customers->getShippingAddress($customerId, $addressId);

        $order = DB::transaction(function () use ($cart, $customerId, $address) {
            $order = Order::create([
                'order_number' => 'ORD-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6)),
                'customer_id' => $customerId,
                'shipping_name' => $address->recipientName,
                'shipping_phone' => $address->phone,
                'shipping_address' => $address->formatted(),
                'total_amount' => 0,
            ]);

            $total = 0.0;

            foreach ($cart->items as $item) {
                $snapshot = $this->products->getSnapshot($item->product_id);

                // Melempar InsufficientStockException jika stok kurang -> semua di-rollback.
                $this->products->decreaseStock($item->product_id, $item->quantity);

                $subtotal = $snapshot->price * $item->quantity;
                $total += $subtotal;

                $order->items()->create([
                    'product_id' => $item->product_id,
                    'product_name' => $snapshot->name,
                    'unit_price' => $snapshot->price,
                    'quantity' => $item->quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $total]);
            $cart->items()->delete();

            return $order;
        });

        OrderPlaced::dispatch(
            $order->id,
            $customerId,
            (float) $order->total_amount,
            $order->items->map(fn($i) => [
                'product_id' => $i->product_id,
                'quantity' => $i->quantity,
            ])->all(),
        );

        return $order;
    }
}
