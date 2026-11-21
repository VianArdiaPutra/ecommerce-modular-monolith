<?php

namespace App\Modules\Order\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Customer\Contracts\CustomerServiceInterface;
use App\Modules\Order\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(private CustomerServiceInterface $customers) {}

    public function index(Request $request): View
    {
        $orders = Order::where('customer_id', $this->customerId($request))
            ->latest()
            ->get();

        return view('order::orders.index', ['orders' => $orders]);
    }

    public function show(Request $request, int $order): View
    {
        return view('order::orders.show', ['order' => $this->findOwnOrder($request, $order)]);
    }

    public function success(Request $request, int $order): View
    {
        return view('order::orders.success', ['order' => $this->findOwnOrder($request, $order)]);
    }

    /**
     * Hanya pesanan milik customer yang sedang login.
     */
    private function findOwnOrder(Request $request, int $orderId): Order
    {
        return Order::with('items')
            ->where('customer_id', $this->customerId($request))
            ->findOrFail($orderId);
    }

    private function customerId(Request $request): int
    {
        return $this->customers->getCustomerIdByUserId($request->user()->id);
    }
}
