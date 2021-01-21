<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;
use Spatie\Async\Pool;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $course = Course::create([
            'title' => 'Pelatihan Flutter Dasar 30 Hari',
            'description' => 'Program Learn Flutter Basic in 30 Day.',
            'difficulty' => 'Beginner',
            'price' => 300000.00
        ]);
        $course->categories()->attach([1, 2]);
        $course->instructors()->attach(1);
        $course->inspectors()->attach(1);
    }
}
