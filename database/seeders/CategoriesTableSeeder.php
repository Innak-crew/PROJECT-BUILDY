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
            ['name' => 'Patio', 'type' => 'Exterior'],
            ['name' => 'Foyer', 'type' => 'Interior'],
            ['name' => 'Hallway', 'type' => 'Interior'],
            ['name' => 'Staircase', 'type' => 'Both'],
            ['name' => 'Attic', 'type' => 'Interior'],
            ['name' => 'Basement', 'type' => 'Interior'],
            ['name' => 'Swimming Pool Area', 'type' => 'Exterior'],
            ['name' => 'Utility Room', 'type' => 'Interior'],
            ['name' => 'Laundry Room', 'type' => 'Interior'],
            ['name' => 'Terrace', 'type' => 'Both'],
            ['name' => 'Roof', 'type' => 'Exterior'],
        ]);

        // Subcategories with parent_id
        DB::table('categories')->insert([

            ['name' => 'Kitchen Cupboard', 'parent_id' => 1, 'type' => 'Interior'],
            ['name' => 'Kitchen Wall Unit', 'parent_id' => 1, 'type' => 'Interior'],
            ['name' => 'Kitchen Loft Door', 'parent_id' => 1, 'type' => 'Interior'],
            ['name' => 'Kitchen Countertop', 'parent_id' => 1, 'type' => 'Interior'],
            ['name' => 'Kitchen Backsplash', 'parent_id' => 1, 'type' => 'Interior'],

            ['name' => 'Living Room Cabinet', 'parent_id' => 2, 'type' => 'Interior'],
            ['name' => 'Living Room TV Unit', 'parent_id' => 2, 'type' => 'Interior'],
            ['name' => 'Living Room Shelves', 'parent_id' => 2, 'type' => 'Interior'],
            ['name' => 'Living Room Sofa', 'parent_id' => 2, 'type' => 'Interior'],
            ['name' => 'Living Room Coffee Table', 'parent_id' => 2, 'type' => 'Interior'],

            ['name' => 'Bedroom Wardrobe', 'parent_id' => 3, 'type' => 'Interior'],
            ['name' => 'Bedroom Study Table', 'parent_id' => 3, 'type' => 'Interior'],
            ['name' => 'Bedroom Loft Door', 'parent_id' => 3, 'type' => 'Interior'],
            ['name' => 'Bedroom Bed Frame', 'parent_id' => 3, 'type' => 'Interior'],
            ['name' => 'Bedroom Nightstand', 'parent_id' => 3, 'type' => 'Interior'],

            ['name' => 'Bathroom Vanity Unit', 'parent_id' => 4, 'type' => 'Interior'],
            ['name' => 'Bathroom Wall Cabinet', 'parent_id' => 4, 'type' => 'Interior'],
            ['name' => 'Bathroom Mirror Frame', 'parent_id' => 4, 'type' => 'Interior'],
            ['name' => 'Bathroom Shower Enclosure', 'parent_id' => 4, 'type' => 'Interior'],
            ['name' => 'Bathroom Sink Cabinet', 'parent_id' => 4, 'type' => 'Interior'],

            ['name' => 'Dining Room Table', 'parent_id' => 5, 'type' => 'Interior'],
            ['name' => 'Dining Room Chairs', 'parent_id' => 5, 'type' => 'Interior'],
            ['name' => 'Dining Room Sideboard', 'parent_id' => 5, 'type' => 'Interior'],
            ['name' => 'Dining Room Hutch', 'parent_id' => 5, 'type' => 'Interior'],
            ['name' => 'Dining Room Bar Cabinet', 'parent_id' => 5, 'type' => 'Interior'],

            ['name' => 'Office Desk', 'parent_id' => 6, 'type' => 'Interior'],
            ['name' => 'Office Chair', 'parent_id' => 6, 'type' => 'Interior'],
            ['name' => 'Office Shelves', 'parent_id' => 6, 'type' => 'Interior'],
            ['name' => 'Office Cabinet', 'parent_id' => 6, 'type' => 'Interior'],
            ['name' => 'Office Bookcase', 'parent_id' => 6, 'type' => 'Interior'],

            ['name' => 'Garage Shelves', 'parent_id' => 7, 'type' => 'Exterior'],
            ['name' => 'Garage Workbench', 'parent_id' => 7, 'type' => 'Exterior'],
            ['name' => 'Garage Storage Cabinet', 'parent_id' => 7, 'type' => 'Exterior'],
            ['name' => 'Garage Tool Rack', 'parent_id' => 7, 'type' => 'Exterior'],
            ['name' => 'Garage Door', 'parent_id' => 7, 'type' => 'Exterior'],

            ['name' => 'Garden Shed', 'parent_id' => 8, 'type' => 'Exterior'],
            ['name' => 'Garden Bench', 'parent_id' => 8, 'type' => 'Exterior'],
            ['name' => 'Garden Fence', 'parent_id' => 8, 'type' => 'Exterior'],
            ['name' => 'Garden Pergola', 'parent_id' => 8, 'type' => 'Exterior'],
            ['name' => 'Garden Gazebo', 'parent_id' => 8, 'type' => 'Exterior'],

            ['name' => 'Balcony Railing', 'parent_id' => 9, 'type' => 'Both'],
            ['name' => 'Balcony Furniture', 'parent_id' => 9, 'type' => 'Both'],
            ['name' => 'Balcony Planter', 'parent_id' => 9, 'type' => 'Both'],
            ['name' => 'Balcony Awning', 'parent_id' => 9, 'type' => 'Both'],
            ['name' => 'Balcony Decking', 'parent_id' => 9, 'type' => 'Both'],

            ['name' => 'Patio Furniture', 'parent_id' => 10, 'type' => 'Exterior'],
            ['name' => 'Patio Umbrella', 'parent_id' => 10, 'type' => 'Exterior'],
            ['name' => 'Patio Paving', 'parent_id' => 10, 'type' => 'Exterior'],
            ['name' => 'Patio Fire Pit', 'parent_id' => 10, 'type' => 'Exterior'],
            ['name' => 'Patio Pergola', 'parent_id' => 10, 'type' => 'Exterior'],

            ['name' => 'Foyer Bench', 'parent_id' => 11, 'type' => 'Exterior'],
            ['name' => 'Foyer Coat Rack', 'parent_id' => 11, 'type' => 'Exterior'],
            ['name' => 'Foyer Console Table', 'parent_id' => 11, 'type' => 'Exterior'],
            ['name' => 'Foyer Mirror', 'parent_id' => 11, 'type' => 'Exterior'],
            ['name' => 'Foyer Shoe Cabinet', 'parent_id' => 11, 'type' => 'Exterior'],

            ['name' => 'Hallway Console Table', 'parent_id' => 12, 'type' => 'Interior'],
            ['name' => 'Hallway Bench', 'parent_id' => 12, 'type' => 'Interior'],
            ['name' => 'Hallway Mirror', 'parent_id' => 12, 'type' => 'Interior'],
            ['name' => 'Hallway Coat Rack', 'parent_id' => 12, 'type' => 'Interior'],
            ['name' => 'Hallway Shelves', 'parent_id' => 12, 'type' => 'Interior'],

            ['name' => 'Staircase Railing', 'parent_id' => 13, 'type' => 'Both'],
            ['name' => 'Staircase Treads', 'parent_id' => 13, 'type' => 'Both'],
            ['name' => 'Staircase Risers', 'parent_id' => 13, 'type' => 'Both'],
            ['name' => 'Staircase Newel Posts', 'parent_id' => 13, 'type' => 'Both'],
            ['name' => 'Staircase Balusters', 'parent_id' => 13, 'type' => 'Both'],

            ['name' => 'Attic Shelving', 'parent_id' => 14, 'type' => 'Interior'],
            ['name' => 'Attic Storage Bins', 'parent_id' => 14, 'type' => 'Interior'],
            ['name' => 'Attic Insulation', 'parent_id' => 14, 'type' => 'Interior'],
            ['name' => 'Attic Flooring', 'parent_id' => 14, 'type' => 'Interior'],
            ['name' => 'Attic Ladder', 'parent_id' => 14, 'type' => 'Interior'],

            ['name' => 'Basement Shelving', 'parent_id' => 15, 'type' => 'Interior'],
            ['name' => 'Basement Workbench', 'parent_id' => 15, 'type' => 'Interior'],
            ['name' => 'Basement Storage Cabinet', 'parent_id' => 15, 'type' => 'Interior'],
            ['name' => 'Basement Flooring', 'parent_id' => 15, 'type' => 'Interior'],
            ['name' => 'Basement Insulation', 'parent_id' => 15, 'type' => 'Interior'],

            ['name' => 'Pool Decking', 'parent_id' => 16, 'type' => 'Exterior'],
            ['name' => 'Pool Furniture', 'parent_id' => 16, 'type' => 'Exterior'],
            ['name' => 'Pool Fencet', 'parent_id' => 16, 'type' => 'Exterior'],
            ['name' => 'Pool Shed', 'parent_id' => 16, 'type' => 'Exterior'],
            ['name' => 'Pool Cover', 'parent_id' => 16, 'type' => 'Exterior'],

            ['name' => 'Utility Room Shelving', 'parent_id' => 17, 'type' => 'Interior'],
            ['name' => 'Utility Room Workbench', 'parent_id' => 17, 'type' => 'Interior'],
            ['name' => 'Utility Room Storage Cabinet', 'parent_id' => 17, 'type' => 'Interior'],
            ['name' => 'Utility Room Sink', 'parent_id' => 17, 'type' => 'Interior'],
            ['name' => 'Utility Room Dryer Vent', 'parent_id' => 17, 'type' => 'Interior'],

            ['name' => 'Laundry Room Shelving', 'parent_id' => 18, 'type' => 'Interior'],
            ['name' => 'Laundry Room Sink', 'parent_id' => 18, 'type' => 'Interior'],
            ['name' => 'Laundry Room Cabinet', 'parent_id' => 18, 'type' => 'Interior'],
            ['name' => 'Laundry Room Drying Rack', 'parent_id' => 18, 'type' => 'Interior'],
            ['name' => 'Laundry Room Folding Table', 'parent_id' => 18, 'type' => 'Interior'],

            ['name' => 'Terrace Furniture', 'parent_id' => 19, 'type' => 'Both'],
            ['name' => 'Terrace Planter', 'parent_id' => 19, 'type' => 'Both'],
            ['name' => 'Terrace Railing', 'parent_id' => 19, 'type' => 'Both'],
            ['name' => 'Terrace Awning', 'parent_id' => 19, 'type' => 'Both'],
            ['name' => 'Terrace Decking', 'parent_id' => 19, 'type' => 'Both'],

            ['name' => 'Roof Shingles', 'parent_id' => 20, 'type' => 'Exterior'],
            ['name' => 'Roof Insulation', 'parent_id' => 20, 'type' => 'Exterior'],
            ['name' => 'Roof Ventilation', 'parent_id' => 20, 'type' => 'Exterior'],
            ['name' => 'Roof Skylight', 'parent_id' => 20, 'type' => 'Exterior'],
            ['name' => 'Roof Gutter', 'parent_id' => 20, 'type' => 'Exterior'],

        ]);
    }
}
