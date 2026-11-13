<?php

namespace App\Modules\Catalog\Contracts;

interface ProductServiceInterface
{
    public function getSnapshot(int $productId): ProductSnapshot;

    public function hasStock(int $productId, int $quantity): bool;

    public function decreaseStock(int $productId, int $quantity): void;
}
