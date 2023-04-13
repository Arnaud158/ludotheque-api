<?php

namespace Database\Seeders;

use App\Models\Adherent;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdherentsSeeder extends Seeder
{

    public static int $numberOfUserAdded = 10;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Adherent::factory([
            'name' => "Administrateur",
            'email' => "administrateur.duchmol@domain.fr",
            'password' => Hash::make('GrosSecret'),
            'remember_token' => Str::random(10),
            'nom' => "Duchmol",
            'prenom' => "Administrateur",
            'pseudo' => "AdministrateurDuchmol"
        ])->create();

        Adherent::factory([
            'name' => "Commentaire moderateur",
            'email' => "commentaire.moderateur.duchmol@domain.fr",
            'password' => Hash::make('GrosSecret'),
            'remember_token' => Str::random(10),
            'nom' => "Duchmol",
            'prenom' => "Commentaire-moderateur",
            'pseudo' => "CommentaireModerateurDuchmol"
        ])->create();

        Adherent::factory([
            'name' => "Adhérent premium",
            'email' => "adherent.premium.duchmol@domain.fr",
            'password' => Hash::make('GrosSecret'),
            'remember_token' => Str::random(10),
            'nom' => "Duchmol",
            'prenom' => "Adhérent-premium",
            'pseudo' => "AdhérentPremium"
        ])->create();

        Adherent::factory([
            'name' => "Adherent",
            'email' => "adherent.duchmol@domain.fr",
            'password' => Hash::make('GrosSecret'),
            'remember_token' => Str::random(10),
            'nom' => "Duchmol",
            'prenom' => "Adherent",
            'pseudo' => "Adherent"
        ])->create();

        Adherent::factory(AdherentsSeeder::$numberOfUserAdded)->create();

    }
}
