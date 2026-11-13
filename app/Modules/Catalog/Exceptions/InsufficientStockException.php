<?php

namespace App\Modules\Catalog\Exceptions;

use RuntimeException;

class InsufficientStockException extends RuntimeException
{
    public static function forProduct(string $productName, int $available, int $requested): self
    {
        return new self(
            "Stok {$productName} tidak mencukupi. Tersedia {$available}, diminta {$requested}."
        );
    }
}
