<?php

namespace Database\Seeders;

use App\Models\Categorie;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Categorie::factory([
            'nom' => "Enfant"
        ])->create();

        Categorie::factory([
            'nom' => "Stratégique"
        ])->create();

        Categorie::factory([
            'nom' => "Plateau"
        ])->create();

    }
}
