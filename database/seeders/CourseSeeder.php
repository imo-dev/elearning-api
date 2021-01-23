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
        $course->topics()->create(['name' => 'Bab 1']);
        $course->topics()->create(['name' => 'Bab 2']);
        $course->topics()->create(['name' => 'Bab 3']);
        $course->materials()->create(['title' => 'Pengumuman', 'description' => 'haha']);
        $course->materials()->create(['topic_id' => 1, 'title' => 'Materi Bab 1', 'description' => 'haha']);
        $course->materials()->create(['topic_id' => 2, 'title' => 'Materi Bab 2', 'description' => 'haha']);
        $course->materials()->create(['topic_id' => 3, 'title' => 'Materi Bab 3', 'description' => 'haha']);
    }
}
