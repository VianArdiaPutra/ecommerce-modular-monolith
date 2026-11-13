<?php

namespace App\Modules\Catalog\Contracts;

final readonly class ProductSnapshot
{
    public function __construct(
        public int $id,
        public string $name,
        public float $price,
        public int $stock,
        public bool $isActive,
    ) {}
}
