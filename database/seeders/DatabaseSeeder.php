<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        $this->call(CategorieSeeder::class);
        $this->call(ThemeSeeder::class);
        $this->call(EditeurSeeder::class);
        $this->call(JeuSeeder::class);
    }
}
