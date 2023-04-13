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
        // \App\Models\Adherent::factory()->create([
        //     'name' => 'Test Adherent',
        //     'email' => 'test@example.com',
        // ]);

        $this->call(RolesSeeder::class);
        $this->call(AdherentsSeeder::class);
        $this->call(RolesAdherentSeeder::class);

        $this->call(CategorieSeeder::class);
        $this->call(ThemeSeeder::class);
        $this->call(EditeurSeeder::class);
        $this->call(JeuSeeder::class);
    }
}
