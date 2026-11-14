<?php

namespace App\Modules\Customer\Services;

use App\Modules\Customer\Contracts\AddressSnapshot;
use App\Modules\Customer\Contracts\CustomerServiceInterface;
use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;

class CustomerService implements CustomerServiceInterface
{
    public function getCustomerIdByUserId(int $userId): int
    {
        return Customer::where('user_id', $userId)->firstOrFail()->id;
    }

    public function getAddresses(int $customerId): array
    {
        return Address::where('customer_id', $customerId)
            ->orderByDesc('is_default')
            ->get()
            ->map(fn(Address $address) => $this->toSnapshot($address))
            ->all();
    }

    public function getShippingAddress(int $customerId, int $addressId): AddressSnapshot
    {
        $address = Address::where('customer_id', $customerId)->findOrFail($addressId);

        return $this->toSnapshot($address);
    }

    private function toSnapshot(Address $address): AddressSnapshot
    {
        return new AddressSnapshot(
            id: $address->id,
            label: $address->label,
            recipientName: $address->recipient_name,
            phone: $address->phone,
            fullAddress: $address->full_address,
            city: $address->city,
            postalCode: $address->postal_code,
            isDefault: $address->is_default,
        );
    }
}
