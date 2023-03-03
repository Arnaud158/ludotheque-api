<?php

namespace App\Http\Controllers;

use App\Http\Resources\JeuResource;
use App\Models\Jeu;
use App\Models\Tache;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use function MongoDB\BSON\toJSON;

class JeuControleur extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $jeux = Jeu::all();
        return view('jeux.index', ['jeux' => $jeux]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
    public function show(Request $request, $id)
    {
        $action = $request->query('action', 'show');
        $jeu = Jeu::find($id);

        return view('jeux.show', ['jeu' => $jeu, 'action' => $action]);
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

    public function listeJeu() {
        $user = Auth::check();
        if ($user) {

        }
        else {
            $jeux = Jeu::all()->random(5);
            return new JeuResource($jeux);
        }
    }
}
