<?php

namespace App\Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Catalog\Exceptions\InsufficientStockException;
use App\Modules\Customer\Contracts\CustomerServiceInterface;
use App\Modules\Order\Exceptions\EmptyCartException;
use App\Modules\Order\Http\Requests\CheckoutRequest;
use App\Modules\Order\Services\CartService;
use App\Modules\Order\Services\CheckoutService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(
        private CartService $cart,
        private CheckoutService $checkout,
        private CustomerServiceInterface $customers,
    ) {}

    public function show(Request $request): View|RedirectResponse
    {
        $customerId = $this->customerId($request);
        $details = $this->cart->getCartDetails($customerId);

        if ($details['items'] === []) {
            return redirect()->route('cart.index')->with('error', 'Keranjang masih kosong.');
        }

        return view('order::checkout.show', $details + [
            'addresses' => $this->customers->getAddresses($customerId),
        ]);
    }

    public function store(CheckoutRequest $request): RedirectResponse
    {
        try {
            $order = $this->checkout->checkout(
                $this->customerId($request),
                (int) $request->validated('address_id'),
            );
        } catch (EmptyCartException | InsufficientStockException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        return redirect()
            ->route('orders.show', $order->id)
            ->with('success', "Pesanan {$order->order_number} berhasil dibuat.");
    }

    private function customerId(Request $request): int
    {
        return $this->customers->getCustomerIdByUserId($request->user()->id);
    }
}
