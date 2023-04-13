<?php

namespace Database\Seeders;

use App\Models\Editeur;
use Illuminate\Database\Seeder;

class EditeurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Editeur::factory([
            'nom' => 'Hasbro',
        ])->create();

        Editeur::factory([
            'nom' => 'Mme Membré',
        ])->create();

        Editeur::factory([
            'nom' => 'Ludo',
        ])->create();
    }
}
