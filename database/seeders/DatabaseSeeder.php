<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeders.
     */
    public function run(): void
    {
        $this->call([
            CourseSeeder::class,
            TutorSeeder::class,
            StudentSeeder::class,
            ModuleErstiwocheSeeder::class,
            EventsErstiwocheSeeder::class,
            PageErstiwocheSeeder::class,
            // ModuleGerolsteinSeeder::class,
            // EventsGerolsteinSeeder::class,
            // PageGerolsteinSeeder::class,
        ]);
    }
}
