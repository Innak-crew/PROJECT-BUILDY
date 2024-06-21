<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use Illuminate\Support\Facades\DB;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('categories')->insert([
            ['name' => 'Kitchen', 'type' => 'Interior'],
            ['name' => 'Living Room', 'type' => 'Interior'],
            ['name' => 'Bedroom', 'type' => 'Interior'],
            ['name' => 'Bathroom', 'type' => 'Interior'],
            ['name' => 'Dining Room', 'type' => 'Interior'],
            ['name' => 'Home Office', 'type' => 'Interior'],
            ['name' => 'Garage', 'type' => 'Exterior'],
            ['name' => 'Garden', 'type' => 'Exterior'],
            ['name' => 'Balcony', 'type' => 'Both'],
        ]);

        // Subcategories with parent_id
        DB::table('categories')->insert([

            ['name' => 'Cupboard', 'parent_id' => 1, 'type' => 'Interior'],
            ['name' => 'Wall Unit', 'parent_id' => 1, 'type' => 'Interior'],
            ['name' => 'Loft Door', 'parent_id' => 1, 'type' => 'Interior'],
            ['name' => 'Countertop', 'parent_id' => 1, 'type' => 'Interior'],
            ['name' => 'Backsplash', 'parent_id' => 1, 'type' => 'Interior'],

            ['name' => 'Cabinet', 'parent_id' => 2, 'type' => 'Interior'],
            ['name' => 'TV Unit', 'parent_id' => 2, 'type' => 'Interior'],
            ['name' => 'Shelves', 'parent_id' => 2, 'type' => 'Interior'],
            ['name' => 'Sofa', 'parent_id' => 2, 'type' => 'Interior'],
            ['name' => 'Coffee Table', 'parent_id' => 2, 'type' => 'Interior'],

            ['name' => 'Wardrobe', 'parent_id' => 3, 'type' => 'Interior'],
            ['name' => 'Study Table', 'parent_id' => 3, 'type' => 'Interior'],
            ['name' => 'Loft Door', 'parent_id' => 3, 'type' => 'Interior'],
            ['name' => 'Bed Frame', 'parent_id' => 3, 'type' => 'Interior'],
            ['name' => 'Nightstand', 'parent_id' => 3, 'type' => 'Interior'],

            ['name' => 'Vanity Unit', 'parent_id' => 4, 'type' => 'Interior'],
            ['name' => 'Wall Cabinet', 'parent_id' => 4, 'type' => 'Interior'],
            ['name' => 'Mirror Frame', 'parent_id' => 4, 'type' => 'Interior'],
            ['name' => 'Shower Enclosure', 'parent_id' => 4, 'type' => 'Interior'],
            ['name' => 'Sink Cabinet', 'parent_id' => 4, 'type' => 'Interior'],

            ['name' => 'Table', 'parent_id' => 5, 'type' => 'Interior'],
            ['name' => 'Chairs', 'parent_id' => 5, 'type' => 'Interior'],
            ['name' => 'Sideboard', 'parent_id' => 5, 'type' => 'Interior'],
            ['name' => 'Hutch', 'parent_id' => 5, 'type' => 'Interior'],
            ['name' => 'Bar Cabinet', 'parent_id' => 5, 'type' => 'Interior'],

            ['name' => 'Desk', 'parent_id' => 6, 'type' => 'Interior'],
            ['name' => 'Chair', 'parent_id' => 6, 'type' => 'Interior'],
            ['name' => 'Shelves', 'parent_id' => 6, 'type' => 'Interior'],
            ['name' => 'Cabinet', 'parent_id' => 6, 'type' => 'Interior'],
            ['name' => 'Bookcase', 'parent_id' => 6, 'type' => 'Interior'],

            ['name' => 'Shelves', 'parent_id' => 7, 'type' => 'Exterior'],
            ['name' => 'Workbench', 'parent_id' => 7, 'type' => 'Exterior'],
            ['name' => 'Storage Cabinet', 'parent_id' => 7, 'type' => 'Exterior'],
            ['name' => 'Tool Rack', 'parent_id' => 7, 'type' => 'Exterior'],
            ['name' => 'Door', 'parent_id' => 7, 'type' => 'Exterior'],

            ['name' => 'Shed', 'parent_id' => 8, 'type' => 'Exterior'],
            ['name' => 'Bench', 'parent_id' => 8, 'type' => 'Exterior'],
            ['name' => 'Fence', 'parent_id' => 8, 'type' => 'Exterior'],
            ['name' => 'Pergola', 'parent_id' => 8, 'type' => 'Exterior'],
            ['name' => 'Gazebo', 'parent_id' => 8, 'type' => 'Exterior'],

            ['name' => 'Railing', 'parent_id' => 9, 'type' => 'Both'],
            ['name' => 'Furniture', 'parent_id' => 9, 'type' => 'Both'],
            ['name' => 'Planter', 'parent_id' => 9, 'type' => 'Both'],
            ['name' => 'Awning', 'parent_id' => 9, 'type' => 'Both'],
            ['name' => 'Decking', 'parent_id' => 9, 'type' => 'Both'],

        ]);
    }
}
