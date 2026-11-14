<?php

namespace Database\Seeders;

use App\Modules\Catalog\Database\Seeders\CatalogSeeder;
use App\Modules\Customer\Database\Seeders\CustomerSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CatalogSeeder::class,
            CustomerSeeder::class,
        ]);
    }
}
