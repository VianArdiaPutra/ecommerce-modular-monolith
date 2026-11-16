<?php

namespace App\Modules\Order\Events;

use Illuminate\Foundation\Events\Dispatchable;

class OrderPlaced
{
    use Dispatchable;

    public function __construct(
        public readonly int $orderId,
        public readonly int $customerId,
        public readonly float $totalAmount,
        public readonly array $items,
    ) {}
}
