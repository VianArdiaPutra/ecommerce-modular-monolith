<?php

namespace App\Modules\Customer\Contracts;

final readonly class AddressSnapshot
{
    public function __construct(
        public int $id,
        public string $label,
        public string $recipientName,
        public string $phone,
        public string $fullAddress,
        public string $city,
        public string $postalCode,
        public bool $isDefault,
    ) {}

    public function formatted(): string
    {
        return "{$this->fullAddress}, {$this->city} {$this->postalCode}";
    }
}
