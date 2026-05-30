<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PositionSeeder::class,
            PptkKategoriSeeder::class,
            WorkflowSeeder::class,
            WorkflowStepRkbuSeeder::class,
            WorkflowStepUsulanSeeder::class,
        ]);
    }
}
