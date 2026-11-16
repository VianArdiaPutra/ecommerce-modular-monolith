<?php

namespace App\Modules\Order\Exceptions;

use RuntimeException;

class EmptyCartException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Keranjang masih kosong. Tambahkan produk sebelum checkout.');
    }
}
