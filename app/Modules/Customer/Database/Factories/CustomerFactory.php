<?php

namespace App\Modules\Customer\Database\Factories;

use App\Models\User;
use App\Modules\Customer\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

class CustomerFactory extends Factory
{
    protected $model = Customer::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'phone' => '08' . fake()->numerify('##########'),
        ];
    }
}
