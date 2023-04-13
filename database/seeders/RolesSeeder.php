<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::factory()->create([
            'nom' => 'administrateur',
        ]);
        Role::factory()->create([
            'nom' => 'commentaire-moderateur',
        ]);
        Role::factory()->create([
            'nom' => 'adhérent-premium',
        ]);
        Role::factory()->create([
            'nom' => 'adherent',
        ]);
    }
}
