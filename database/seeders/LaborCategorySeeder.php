<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LaborCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('labor_categories')->insert([
            [
                'name' => 'Mason',
                'description' => 'A skilled worker who builds with concrete, stone, and brick.'
            ],
            [
                'name' => 'Carpenter',
                'description' => 'A skilled worker who works with wood.'
            ],
            [
                'name' => 'Electrician',
                'description' => 'A worker skilled in electrical wiring and installation.'
            ],
            [
                'name' => 'Plumber',
                'description' => 'A worker skilled in installing and repairing pipes and fixtures.'
            ],
            [
                'name' => 'Painter',
                'description' => 'A skilled worker who applies paint and finishes to various surfaces.'
            ],
            [
                'name' => 'Heavy Equipment Operator',
                'description' => 'A person who operates heavy machinery used in construction.'
            ],
            [
                'name' => 'Tile Setter',
                'description' => 'A skilled worker who lays tiles on floors and walls.'
            ],
            [
                'name' => 'Laborer',
                'description' => 'A general worker who assists in various construction tasks.'
            ],
            [
                'name' => 'Welder',
                'description' => 'A skilled worker who fuses materials together using heat.'
            ],
            [
                'name' => 'Site Supervisor',
                'description' => 'A person who oversees the work on a construction site.'
            ],
            [
                'name' => 'Painter Assistant',
                'description' => 'An assistant to the painter, helping with preparations and cleanup.'
            ],
            [
                'name' => 'Plumbing Assistant',
                'description' => 'An assistant to the plumber, helping with tasks like fetching materials and cleanup.'
            ],
            [
                'name' => 'Electrician Assistant',
                'description' => 'An assistant to the electrician, helping with tasks like fetching tools and cleanup.'
            ],
        ]);
    }
}
