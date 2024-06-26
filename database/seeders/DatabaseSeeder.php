<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        $this->call(AdminSeeder::class);
        $this->call(QuantityUnitsSeeder::class);
        $this->call(CategoriesTableSeeder::class);
        // $this->call(ProductsTableSeeder::class);
        $this->call(CategoryKeySeeder::class);
        $this->call(DesignsTableSeeder::class);
        $this->call(LaborCategorySeeder::class);
    }
}
