<?php

namespace App\Http\Controllers;

use App\Http\Requests\JeuRequest;
use App\Models\Jeu;
use Exception;
use Illuminate\Http\Request;

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
    public function create(JeuRequest $request)
    {

        $jeu = new Jeu();
        $jeu->id_jeu = $request->id_jeu;
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

        return response()->json([
            'status' => true,
            'message' => 'Game created successfully',
            'jeu' => $jeu
        ]);
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


    /**
     * @OA\Put(
     *      path="/Jeus/{id}",
     *      tags={"Jeu"},
     *      summary="Met à jour un jeu",
     *          @OA\Response(
     *              response="200", description="L opération a fonctionnée.",
     *          ),
     *      @OA\Response(response="422", description="Unprocessable Entity",)
     *  ),
     */
    public function update(JeuRequest $request, $id)
    {
        try {
            $jeu = Jeu::findOrFail($id);
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

            return response()->json([
                'status' => true,
                'message' => 'updated successfully'
            ]);
        } catch (Exception) {
            return response()->json([
                'status' => false,
                'message' => 'could not update the item'
            ]);
        }
    }
}
