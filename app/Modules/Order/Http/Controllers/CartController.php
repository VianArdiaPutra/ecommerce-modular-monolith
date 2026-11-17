<?php

namespace App\Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Exceptions\InsufficientStockException;
use App\Modules\Customer\Contracts\CustomerServiceInterface;
use App\Modules\Order\Http\Requests\AddToCartRequest;
use App\Modules\Order\Http\Requests\UpdateCartRequest;
use App\Modules\Order\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CustomerServiceInterface $customers,
    ) {}

    public function index(Request $request): View
    {
        $details = $this->cart->getCartDetails($this->customerId($request));

        return view('order::cart.index', $details);
    }

    public function add(AddToCartRequest $request): RedirectResponse
    {
        try {
            $this->cart->addItem(
                $this->customerId($request),
                (int) $request->validated('product_id'),
                (int) $request->validated('quantity'),
            );
        } catch (InsufficientStockException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(UpdateCartRequest $request, int $productId): RedirectResponse
    {
        try {
            $this->cart->updateQuantity(
                $this->customerId($request),
                $productId,
                (int) $request->validated('quantity'),
            );
        } catch (InsufficientStockException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Keranjang diperbarui.');
    }

    public function remove(Request $request, int $productId): RedirectResponse
    {
        $this->cart->removeItem($this->customerId($request), $productId);

        return back()->with('success', 'Produk dihapus dari keranjang.');
    }

    private function customerId(Request $request): int
    {
        return $this->customers->getCustomerIdByUserId($request->user()->id);
    }
}
