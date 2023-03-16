<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentaireRequest;
use App\Models\Commentaire;
use Illuminate\Http\Request;

class CommentaireController extends Controller
{
    public function store(CommentaireRequest $request)
    {

        $commentaire = new Commentaire()  ;
        $commentaire->commentaire = $request->commentaire;
        $commentaire->date_com = $request->date_com;
        $commentaire->note = $request->note;

        $commentaire->save();



        return response()->json([
            'status' => 'success',
            'message' => 'Commentaire created successfully',
            'comment' => $commentaire
        ]);
    }
    public function update(CommentaireRequest $request, $id)
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Commentaire updated successfully',
            'commentaire' => $commentaire
        ], 200);
    }
}
