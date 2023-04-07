<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentaireRequest;
use App\Models\Commentaire;
use Illuminate\Http\Request;

/**
 *  @OA\Schema(
 *      schema="Commentary",
 *      type="object",
 *      required={"commentaire","date_com","note", "etat"},
 *      @OA\Property(property="commentaire", type="string"),
 *      @OA\Property(property="date_com", type="date"),
 *      @OA\Property(property="note", type="int"),
 *      @OA\Property(property="etat", type="string")
 * )
 */

class CommentaireController extends Controller
{

       /**
        *  Store a newly created resource in storage.
        *
        *  @OA\Post(
        *      path="/commentaire/store",
        *      tags={"Commentaire"},
        *      summary="Create commentary.",
        *      description="This method create a commentary with date and mark",
        *      operationId="storeCommentary",
        *      @OA\RequestBody(
        *          description="Elements of the commentary",
        *          required=true,
        *          @OA\MediaType(
        *              mediaType="application/json",
        *              @OA\Schema(
        *                  @OA\Property(
        *                      property="commentaire",
        *                      type="string"
        *                  ),
        *                  @OA\Property(
        *                      property="date_com",
        *                      type="date"
        *                  ),
        *                  @OA\Property(
        *                      property="note",
        *                      type="int"
        *                  )
        *              )
        *          )
        *      ),
        *      @OA\Response(
        *          response="200",
        *          description="Comment created successfully",
        *          @OA\JsonContent(
        *             type="array",
        *             @OA\Items(ref="#/components/schemas/Commentaire")
        *         )
        *      )
        *  )
        */
    public function store(CommentaireRequest $request)
    {

        $commentaire = new Commentaire()  ;
        $commentaire->commentaire = $request->commentaire;
        $commentaire->date_com = $request->date_com;
        $commentaire->note = $request->note;

        $commentaire->save();



        return response()->json([
            'status' => 'success',
            'message' => 'Comment created successfully',
            'comment' => $commentaire
        ]);
    }


    /**
     *  Store a newly created resource in storage.
     *
     *  @OA\Put(
     *      path="/commentaire/update",
     *      tags={"Commentaire"},
     *      summary="Update commentary.",
     *      description="This method update a commentary",
     *      operationId="updateCommentary",
     *      @OA\Response(
     *          response="200",
     *          description="Comment updated successfully",
     *          @OA\JsonContent(
     *             type="array",
     *             @OA\Items(ref="#/components/schemas/Commentaire")
     *         )
     *      )
     *  )
     */
    public function update(CommentaireRequest $request, $id)
    {
        $commentaire = Commentaire::findOrFail($id);
        $commentaire->update($request->all());

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully',
            'commentaire' => $commentaire
        ], 200);
    }

    /**
     *  Store a newly created resource in storage.
     *
     *  @OA\Delete(
     *      path="/commentaire/destroy",
     *      tags={"Commentaire"},
     *      summary="Delete commentary.",
     *      description="This method delete commentary",
     *      operationId="destroyCommentary",
     *      @OA\Response(
     *          response="200",
     *          description="Comment successfully deleted",
     *      )
     *  )
     */
    public function destroy($id)
    {

        $commentaire = Commentaire::findOrFail($id)->delete();

        return response()->json([
            'status'=> 'success',
            'message' => "Comment successfully deleted",
        ], 200);
    }
}
