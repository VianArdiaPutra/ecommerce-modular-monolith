<?php

namespace App\Modules\Customer\Database\Factories;

use App\Modules\Customer\Models\Address;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class AddressFactory extends Factory
{
    protected $model = Address::class;

    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'label' => fake()->randomElement(['Rumah', 'Kantor', 'Kos']),
            'recipient_name' => fake()->name(),
            'phone' => '08' . fake()->numerify('##########'),
            'full_address' => fake('id_ID')->streetAddress(),
            'city' => fake('id_ID')->city(),
            'postal_code' => fake()->numerify('#####'),
            'is_default' => false,
        ];
    }
}
