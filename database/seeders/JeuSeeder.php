<?php

namespace Database\Seeders;

use App\Models\Jeu;
use Illuminate\Database\Seeder;

class JeuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Jeu::factory([
            'nom' => 'Echec',
            'description' => 'Jeu stratégique',
            //            'regles' => "Chaque pion à des mouvements différent...",
            'langue' => 'toutes',
            'url_media' => 'public/images/echec.png',
            'age_min' => 2,
            'nombre_joueurs_min' => 2,
            'nombre_joueurs_max' => 2,
            'duree_partie' => 20,
            'categorie_id' => 1,
            'theme_id' => 1,
            'editeur_id' => 1,
        ])->create();

        Jeu::factory([
            'nom' => 'Dames',
            'description' => 'Jeu stratégique',
            //            'regles' => "Chaque pion à des mouvements différent...",
            'langue' => 'toutes',
            'url_media' => 'public/images/dames.png',
            'age_min' => 2,
            'nombre_joueurs_min' => 2,
            'nombre_joueurs_max' => 2,
            'duree_partie' => 30,
            'categorie_id' => 1,
            'theme_id' => 1,
            'editeur_id' => 1,
        ])->create();

        Jeu::factory([
            'nom' => 'Monopoly',
            'description' => 'Jeu stratégique',
            //            'regles' => "Chaque pion à des mouvements différent...",
            'langue' => 'toutes',
            'url_media' => 'public/images/monopoly.png',
            'age_min' => 2,
            'nombre_joueurs_min' => 2,
            'nombre_joueurs_max' => 2,
            'duree_partie' => 180,
            'categorie_id' => 1,
            'theme_id' => 1,
            'editeur_id' => 1,
        ])->create();

        Jeu::factory([
            'nom' => "Jeu de l'Oie",
            'description' => 'Jeu stratégique',
            //            'regles' => "Chaque pion à des mouvements différent...",
            'langue' => 'toutes',
            'url_media' => 'public/images/oie.png',
            'age_min' => 2,
            'nombre_joueurs_min' => 2,
            'nombre_joueurs_max' => 5,
            'duree_partie' => 15,
            'categorie_id' => 1,
            'theme_id' => 1,
            'editeur_id' => 1,
        ])->create();

        Jeu::factory([
            'nom' => 'Mysterium',
            'description' => 'Jeu stratégique',
            //            'regles' => "Chaque pion à des mouvements différent...",
            'langue' => 'toutes',
            'url_media' => 'public/images/echec.png',
            'age_min' => 2,
            'nombre_joueurs_min' => 2,
            'nombre_joueurs_max' => 2,
            'duree_partie' => 30,
            'categorie_id' => 2,
            'theme_id' => 2,
            'editeur_id' => 2,
        ])->create();

        Jeu::factory([
            'nom' => 'SOS Ouistiti',
            'description' => 'Jeu stratégique',
            //            'regles' => "Chaque pion à des mouvements différent...",
            'langue' => 'toutes',
            'url_media' => 'public/images/echec.png',
            'age_min' => 2,
            'nombre_joueurs_min' => 2,
            'nombre_joueurs_max' => 4,
            'duree_partie' => 5,
            'categorie_id' => 1,
            'theme_id' => 1,
            'editeur_id' => 1,
        ])->create();
    }
}
