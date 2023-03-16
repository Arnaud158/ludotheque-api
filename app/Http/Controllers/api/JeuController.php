<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\JeuRequest;
use App\Http\Resources\JeuResource;
use App\Models\Jeu;
use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JeuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jeux = Jeu::all();
        return new JeuResource($jeux);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(JeuRequest $request)
    {
        $this->validate(
            $request,
            [
                'nom' => 'required',
                'description' => 'required',
                'langue' => 'required',
                'url_media' => 'required',
                'age_min' => 'required',
                'nombre_joureus_min' => 'required',
                'nombre_joueurs_max' => 'required',
                'duree_partie' => 'required',
                'valide' => 'required',
            ]
        );

        $jeu = new Jeu;

        $jeu->nom = $request->nom;
        $jeu->description = $request->description;
        $jeu->langue = $request->langue;
        $jeu->url_media = $request->url_media;
        $jeu->age_min = $request->age_min;
        $jeu->nombre_joureus_min = $request->nombre_joureus_min;
        $jeu->nombre_joueurs_max = $request->nombre_joueurs_max;
        $jeu->duree_partie = $request->duree_partie;
        $jeu->valide = $request->valide;

        $jeu->save();

        return redirect()->route('$jeux.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Jeu  $jeux
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $jeu = Jeu::findOrFail($id);

        return new JeuResource($jeu);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Jeu  $jeux
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Jeu $jeux)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Jeu  $jeux
     * @return \Illuminate\Http\Response
     */
    public function destroy(Jeu $jeux)
    {
        //
    }

    public function listeJeu(Request $request) {
        $user = Auth::check();
        if ($user) {
            $jeux = Jeu::all();
            if ($request->age !=null) {
                $jeux = $jeux->where('age' >= $request->age);
            }
            if ($request->duree !=null) {
                $jeux = $jeux->where('duree' >= $request->duree);
            }
            if ($request->nb_joueurs_min !=null) {
                $jeux = $jeux->where('nombre_joueurs_min' >= $request->nombre_joueurs_min);
            }
            if ($request->nb_joueurs_max !=null) {
                $jeux = $jeux->where('nombre_joueurs_max' >= $request->nombre_joueurs_max);
            }
            if ($request->sort !=null) {
                $jeux = $jeux->sortBy($request->sort);
            }

            if ($request->editeur !=null) {
                $jeux = $jeux->where($jeux->editeur(), "=", $request->editeur);
            }

            if ($request->theme !=null) {
                $jeux = $jeux->where($jeux->themes(), "=", $request->theme);
            }

            if ($request->categorie !=null) {
                $jeux = $jeux->where($jeux->categories(), 'in', $request->categorie);
            }


            // a continuer avec categorie, theme, editeur

            return response()->json([
                "status" => "success",
                "Jeux" => new JeuResource($jeux)
            ],200);
        }
        else {
            $jeux = Jeu::all()->random(5);
            return response()->json([
                "status" => "success",
                "Jeux" => new JeuResource($jeux)
            ],200);
            // si marche pas, juste return new JeuRessource
        }
    }
}
