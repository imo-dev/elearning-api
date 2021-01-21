<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Flutter',
            'description' => 'Flutter is framework and toolkit use dart language for building android and ios app in same code powered by google.'
        ]);
        Category::create([
            'name' => '30 Day Program',
            'description' => 'Program For Learn in 30 Day.'
        ]);
    }
}
