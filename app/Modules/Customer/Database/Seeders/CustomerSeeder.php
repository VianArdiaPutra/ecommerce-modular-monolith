<?php

namespace App\Modules\Customer\Database\Seeders;

use App\Models\User;
use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        // Akun admin (tanpa profil customer).
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            ['name' => 'Admin Toko', 'password' => 'password', 'role' => User::ROLE_ADMIN],
        );

        // Akun customer tetap untuk demo.
        $demoUser = User::updateOrCreate(
            ['email' => 'customer@example.com'],
            ['name' => 'Budi Pelanggan', 'password' => 'password', 'role' => User::ROLE_CUSTOMER],
        );

        $demoCustomer = Customer::firstOrCreate(
            ['user_id' => $demoUser->id],
            ['phone' => '081234567890'],
        );

        Address::factory()->create([
            'customer_id' => $demoCustomer->id,
            'label' => 'Rumah',
            'recipient_name' => $demoUser->name,
            'is_default' => true,
        ]);

        // Customer acak tambahan, masing-masing punya 1-3 alamat.
        Customer::factory(4)->create()->each(function (Customer $customer) {
            Address::factory(rand(1, 3))->create(['customer_id' => $customer->id]);
            $customer->addresses()->first()?->update(['is_default' => true]);
        });
    }
}
