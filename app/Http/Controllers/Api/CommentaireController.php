<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CommentaireRequest;
use App\Models\Adherent;
use App\Models\Commentaire;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CommentaireController extends Controller
{
    public function store(CommentaireRequest $request)
    {

        $currentUser = Auth::id();
        if ($currentUser != $request->adherent_id) {
            return response()->json([
                'status' => "error",
                'message' => 'Unauthorized',
            ], 403);
        }

        $commentaire = new Commentaire();
        $commentaire->commentaire = $request->commentaire;
        $commentaire->date_com = $request->date_com;
        $commentaire->note = $request->note;
        $commentaire->adherent_id = $request->adherent_id;
        $commentaire->jeu_id = $request->jeu_id;

        $commentaire->save();



        return response()->json([
            'status' => 'success',
            'message' => 'Comment created successfully',
            'comment' => $commentaire
        ]);
    }

    public function update(CommentaireRequest $request, $id)
    {
        $commentaire = Commentaire::findOrFail($id);
        $userCommentaire = $commentaire->adherent;
        if (Gate::denies('same-user-or-mod', $userCommentaire)) {
            return response()->json([
                'status' => "error",
                'message' => 'Unauthorized',
        ], 403);
        }

        $commentaire->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully',
            'commentaire' => $commentaire
        ], 200);
    }

    public function destroy($id)
    {
        $commentaire = Commentaire::findOrFail($id);

        $userCommentaire = $commentaire->adherent;
        if (Gate::denies('same-user-or-mod', $userCommentaire)) {
            return response()->json([
                'status' => "error",
                'message' => 'Unauthorized',
        ], 403);
        }

        $commentaire->delete();

        return response()->json([
            'status'=> 'success',
            'message' => "Comment successfully deleted",
        ], 200);
    }
}
