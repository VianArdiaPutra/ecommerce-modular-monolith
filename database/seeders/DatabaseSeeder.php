<?php

namespace Database\Seeders;

use App\Modules\Catalog\Database\Seeders\CatalogSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            CatalogSeeder::class,
        ]);
    }
}
