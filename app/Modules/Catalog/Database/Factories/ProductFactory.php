<?php

namespace App\Modules\Catalog\Database\Factories;

use App\Modules\Catalog\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => Str::title($name),
            'slug' => Str::slug($name),
            'description' => fake()->sentence(12),
            'price' => fake()->numberBetween(10, 2000) * 1000,
            'stock' => fake()->numberBetween(0, 50),
            'is_active' => true,
        ];
    }
}
