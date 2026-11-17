<?php

namespace Tests\Feature;

use App\Modules\Catalog\Exceptions\InsufficientStockException;
use App\Modules\Catalog\Models\Product;
use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;
use App\Modules\Order\Events\OrderPlaced;
use App\Modules\Order\Exceptions\EmptyCartException;
use App\Modules\Order\Models\Order;
use App\Modules\Order\Services\CartService;
use App\Modules\Order\Services\CheckoutService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CheckoutTransactionTest extends TestCase
{
    use RefreshDatabase;

    private CartService $cart;

    private CheckoutService $checkout;

    private Customer $customer;

    private Address $address;

    protected function setUp(): void
    {
        parent::setUp();

        $this->cart = app(CartService::class);
        $this->checkout = app(CheckoutService::class);

        $this->customer = Customer::factory()->create();
        $this->address = Address::factory()->create(['customer_id' => $this->customer->id]);
    }

    public function test_checkout_berhasil_membuat_order_dan_mengurangi_stok(): void
    {
        $product = Product::factory()->create(['price' => 100000, 'stock' => 10]);

        $this->cart->addItem($this->customer->id, $product->id, 2);

        $order = $this->checkout->checkout($this->customer->id, $this->address->id);

        $this->assertSame(Order::STATUS_PENDING, $order->status);
        $this->assertEquals(200000.0, (float) $order->total_amount);
        $this->assertSame(8, $product->fresh()->stock);
        $this->assertCount(1, $order->items);
        $this->assertSame([], $this->cart->getCartDetails($this->customer->id)['items']);
    }

    public function test_checkout_rollback_jika_salah_satu_produk_stoknya_habis(): void
    {
        $productA = Product::factory()->create(['price' => 50000, 'stock' => 10]);
        $productB = Product::factory()->create(['price' => 70000, 'stock' => 5]);

        $this->cart->addItem($this->customer->id, $productA->id, 1);
        $this->cart->addItem($this->customer->id, $productB->id, 1);

        // Stok produk B habis setelah masuk keranjang (dibeli orang lain).
        $productB->update(['stock' => 0]);

        try {
            $this->checkout->checkout($this->customer->id, $this->address->id);
            $this->fail('InsufficientStockException seharusnya dilempar.');
        } catch (InsufficientStockException) {
            // diharapkan
        }

        $this->assertSame(0, Order::count(), 'Order tidak boleh tersimpan.');
        $this->assertSame(10, $productA->fresh()->stock, 'Stok produk A harus kembali (rollback).');
        $this->assertCount(2, $this->cart->getCartDetails($this->customer->id)['items'], 'Keranjang tidak boleh dikosongkan.');
    }

    public function test_checkout_dengan_keranjang_kosong_melempar_exception(): void
    {
        $this->expectException(EmptyCartException::class);

        $this->checkout->checkout($this->customer->id, $this->address->id);
    }

    public function test_customer_tidak_bisa_memakai_alamat_milik_customer_lain(): void
    {
        $product = Product::factory()->create(['stock' => 10]);
        $this->cart->addItem($this->customer->id, $product->id, 1);

        $otherAddress = Address::factory()->create();

        $this->expectException(ModelNotFoundException::class);

        $this->checkout->checkout($this->customer->id, $otherAddress->id);
    }

    public function test_harga_di_order_item_adalah_snapshot(): void
    {
        $product = Product::factory()->create(['price' => 100000, 'stock' => 10]);
        $this->cart->addItem($this->customer->id, $product->id, 1);

        $order = $this->checkout->checkout($this->customer->id, $this->address->id);

        $product->update(['price' => 999999]);

        $this->assertEquals(100000.0, (float) $order->fresh()->items->first()->unit_price);
    }

    public function test_event_order_placed_dikirim_setelah_checkout_berhasil(): void
    {
        Event::fake([OrderPlaced::class]);

        $product = Product::factory()->create(['stock' => 10]);
        $this->cart->addItem($this->customer->id, $product->id, 1);

        $order = $this->checkout->checkout($this->customer->id, $this->address->id);

        Event::assertDispatched(OrderPlaced::class, fn (OrderPlaced $e) => $e->orderId === $order->id);
    }
}
