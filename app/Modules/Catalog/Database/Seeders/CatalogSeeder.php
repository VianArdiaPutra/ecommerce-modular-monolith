<?php

namespace App\Modules\Catalog\Database\Seeders;

use App\Modules\Catalog\Models\Category;
use App\Modules\Catalog\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $categories = collect([
            'Elektronik',
            'Fashion',
            'Rumah Tangga',
            'Olahraga',
            'Buku',
            'Aksesoris',
        ])->map(fn(string $name) => Category::firstOrCreate(
            ['slug' => Str::slug($name)],
            ['name' => $name],
        ));

        Product::factory(20)->create()->each(function (Product $product) use ($categories) {
            $product->categories()->attach(
                $categories->random(rand(1, 2))->pluck('id')
            );
        });
    }
}
