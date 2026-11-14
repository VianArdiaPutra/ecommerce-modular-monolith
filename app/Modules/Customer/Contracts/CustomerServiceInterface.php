<?php

namespace App\Modules\Customer\Contracts;

interface CustomerServiceInterface
{
    public function getCustomerIdByUserId(int $userId): int;

    /**
     * @return list<AddressSnapshot>
     */
    public function getAddresses(int $customerId): array;

    public function getShippingAddress(int $customerId, int $addressId): AddressSnapshot;
}
