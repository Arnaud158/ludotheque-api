<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AchatRequest;
use App\Http\Requests\JeuRequest;
use App\Http\Resources\JeuResource;
use App\Models\Achat;
use App\Models\Categorie;
use App\Models\Editeur;
use App\Models\Jeu;
use App\Models\Theme;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use OpenApi\Annotations as OA;

/**
 * @OA\Tag(
 *     name="Games",
 *     description="Operations related to games.",
 * )
 *
 * @OA\Info(
 *     version="1.0",
 *     title="Ludotech API",
 *     description="Welcome to our Ludotech API!",
 *
 *     @OA\Contact(name="Jules BOBEUF, Thomas SANTORO, Arnaud FIEVET, Elsa LOGIER"),
 * )
 *
 * @OA\Server(
 *     url="http://127.0.0.1:8000/api",
 *     description="Ludotech API Server",
 * )
 *
 * @OA\Schema(
 *      schema="Jeu",
 *
 *      @OA\Property(
 *          property="id",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="nom",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="description",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="regles",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="langue",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="url_media",
 *          type="string",
 *      ),
 *      @OA\Property(
 *          property="age_min",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="nombre_joueurs_min",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="nombre_joueurs_max",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="duree_partie",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="valide",
 *          type="boolean",
 *      ),
 *      @OA\Property(
 *          property="categorie_id",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="theme_id",
 *          type="integer",
 *      ),
 *      @OA\Property(
 *          property="editeur_id",
 *          type="integer",
 *      ),
 * ),
 *
 *  @OA\Schema(
 *      schema="Adherents",
 *      type="object",
 *      required={"id","name","email", "password", "nom", "prenom", "pseudo"},
 *
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="name", type="string"),
 *      @OA\Property(property="email", type="string"),
 *      @OA\Property(property="password", type="string"),
 *      @OA\Property(property="nom", type="string"),
 *      @OA\Property(property="prenom", type="string"),
 *      @OA\Property(property="pseudo", type="string")
 * )
 */
class JeuController extends Controller
{
    /**
     * @OA\Get(
     *      path="/jeux",
     *      tags={"Jeu"},
     *      summary="Returns the list of all Games",
     *
     *          @OA\Response(
     *              response="200", description="Operation succeeded.",
     *
     *              @OA\JsonContent(ref="#/components/schemas/Jeu")
     *          ),
     *
     *          @OA\Response(response="401", description="Some data are missing.",),
     *          @OA\Response(response="403", description="You do not have right access",),
     *  ),
     */
    public function index()
    {
        $jeux = Jeu::all();

        return new JeuResource($jeux);
    }

    /**
     * @OA\Post(
     *      path="/jeux",
     *      tags={"Jeu"},
     *      summary="Stores a game in the database.",
     *
     *          @OA\Response(
     *              response="200", description="Operation succeeded.",
     *
     *              @OA\JsonContent(ref="#/components/schemas/Jeu")
     *          ),
     *
     *          @OA\Response(response="401", description="Some data are missing.",),
     *          @OA\Response(response="403", description="You do not have right access",),
     *  ),
     *),
     */
    public function store(JeuRequest $request)
    {
        $jeu = new Jeu;

        $jeu->nom = $request->nom;
        $jeu->description = $request->description;
        $jeu->langue = $request->langue;
        $jeu->age_min = $request->age_min;
        $jeu->nombre_joueurs_min = $request->nombre_joueurs_min;
        $jeu->nombre_joueurs_max = $request->nombre_joueurs_max;
        $jeu->duree_partie = $request->duree_partie;

        // Gestion de la catégorie

        $categorie = Categorie::where('nom', $request->categorie)->firstOrCreate(['nom' => $request->categorie]);
        $jeu->categorie_id = $categorie->id;

        // Gestion du thème

        $theme = Theme::where('nom', $request->theme)->firstOrCreate(['nom' => $request->theme]);
        $jeu->theme_id = $theme->id;

        // Gestion de l'éditeur

        $editeur = Editeur::where('nom', $request->editeur)->firstOrCreate(['nom' => $request->editeur]);
        $jeu->editeur_id = $editeur->id;

        $jeu->save();

//        return redirect()->route('$jeux.index');
        return response()->json([
            'status' => 'success',
            'message' => 'Game created successfully',
            'jeu' => $jeu,
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/jeux",
     * tags={"Jeu"},
     * summary="It creates a game",
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(),
     *
     *            @OA\Schema(
     *               type="object",
     *               required={"id_jeu","nom","description","langue","url_media","age_min","nombre_joureus_min","nombre_joueurs_max","duree_partie","valide"},
     *
     *               @OA\Property(property="id_jeu", type="integer"),
     *               @OA\Property(property="nom", type="string"),
     *               @OA\Property(property="description", type="integer"),
     *               @OA\Property(property="langue", type="string"),
     *               @OA\Property(property="url_media", type="string"),
     *               @OA\Property(property="age_min", type="integer"),
     *               @OA\Property(property="nombre_joureus_min", type="integer"),
     *               @OA\Property(property="nombre_joueurs_max", type="integer"),
     *               @OA\Property(property="duree_partie", type="integer"),
     *               @OA\Property(property="valide", type="boolean"),
     *            ),
     *    ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Game created successfully",
     *
     *          @OA\JsonContent(
     *            type="array",
     *
     *            @OA\Items(ref="#/components/schemas/Jeu")
     *         )
     *       )
     * )
     */
    public function create(JeuRequest $request)
    {
        $jeu = new Jeu;
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
            'status' => 'success',
            'message' => 'Game created successfully',
            'jeu' => $jeu,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/jeux/{id}",
     *      tags={"Jeu"},
     *      summary="Returns all the informations about one specific game.",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Jeu id",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *          @OA\Response(
     *              response="200", description="Operation succeeded.",
     *
     *              @OA\JsonContent(ref="#/components/schemas/Jeu")
     *          ),
     *
     *          @OA\Response(response="401", description="Some data are missing.",),
     *          @OA\Response(response="403", description="You do not have right access",),
     *  ),
     *),
     */
    public function show($id)
    {
        $jeu = Jeu::findOrFail($id);

        return new JeuResource($jeu);
    }

    /**
     * @OA\Put(
     *      path="/jeux/{id}",
     *      tags={"Jeu"},
     *      summary="Updates the informations of a game.",
     *
     *          @OA\Response(
     *              response="200", description="Operation succeeded.",
     *
     *              @OA\JsonContent(ref="#/components/schemas/Jeu")
     *          ),
     *
     *          @OA\Response(response="401", description="Some data are missing.",),
     *          @OA\Response(response="403", description="You do not have right access",),

     *  ),
     */
    public function update(JeuRequest $request, $id)
    {
        try {
            $jeu = Jeu::findOrFail($id);
            $jeu->nom = $request->nom;
            $jeu->description = $request->description;
            $jeu->langue = $request->langue;
            $jeu->age_min = $request->age_min;
            $jeu->nombre_joueurs_min = $request->nombre_joueurs_min;
            $jeu->nombre_joueurs_max = $request->nombre_joueurs_max;
            $jeu->duree_partie = $request->duree_partie;
            // Gestion de la catégorie

            $categorie = Categorie::where('nom', $request->categorie)->firstOrCreate(['nom' => $request->categorie]);
            $jeu->categorie_id = $categorie->id;

            // Gestion du thème

            $theme = Theme::where('nom', $request->theme)->firstOrCreate(['nom' => $request->theme]);
            $jeu->theme_id = $theme->id;

            // Gestion de l'éditeur

            $editeur = Editeur::where('nom', $request->editeur)->firstOrCreate(['nom' => $request->editeur]);
            $jeu->editeur_id = $editeur->id;

            $jeu->save();

            return response()->json([
                'status' => 'success',
                'message' => 'updated successfully',
                'jeu' => $jeu,
            ]);
        } catch (Exception) {
            return response()->json([
                'status' => false,
                'message' => 'could not update the item',
            ]);
        }
    }

    /**
     * @OA\Delete(
     *      path="/jeux/{id}",
     *      tags={"Jeu"},
     *      summary="Deletes a game.",
     *
     *      @OA\Parameter(
     *          name="id",
     *          description="Jeu id",
     *          required=true,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *          @OA\Response(
     *              response="200", description="Operation succeeded.",
     *
     *              @OA\JsonContent(ref="#/components/schemas/Jeu")
     *          ),
     *
     *          @OA\Response(response="401", description="Some data are missing.",),
     *          @OA\Response(response="403", description="You do not have right access",),

     *  ),
     */
    public function destroy($id)
    {
        try {
            $salle = Jeu::findOrFail($id);
            $salle->delete();

            return response()->json([
                'status' => true,
                'message' => 'deleted successfully',
            ]);
        } catch (Exception) {
            return response()->json([
                'status' => false,
                'message' => 'could not delete the item',
            ]);
        }
    }

    /**
     * @OA\Post(
     *      path="/jeu/listejeu",
     *      tags={"Jeu"},
     *      summary="IF THE USER IS NOT CONNECTED :
           ",
     *     @OA\Parameter(
     *          name="age",
     *          description="youngest age required to play",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="duree",
     *          description="shorter amount of time it takes to play the game.",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="nb_joueurs_min",
     *          description="smallest amount of players required to play the game",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="nb_joueurs_max",
     *          description="highest amount of players required to play the game",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="sort",
     *          description="How you want the games to be sorted (asc, desc)",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="editeur",
     *          description="Choose the editor of the games that will be returned.",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="theme",
     *          description="Choose the theme of the games that will be returned.",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *     @OA\Parameter(
     *          name="categorie",
     *          description="Choose the category of the games that will be returned.",
     *          required=false,
     *          in="path",
     *
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *          @OA\Response(
     *              response="200", description="Operation succeeded.",
     *
     *              @OA\JsonContent(ref="#/components/schemas/Jeu")
     *          ),
     *
     *          @OA\Response(response="401", description="Some data are missing.",),
     *          @OA\Response(response="403", description="You do not have right access",),
     *  )
     */
    public function listeJeu(Request $request)
    {
        $user = auth()->check();
        if ($user) {
            $jeux = Jeu::all();
            if ($request->age != null) {
                $jeux = $jeux->where('age_min', '>=', $request->age);
            }
            if ($request->duree != null) {
                $jeux = $jeux->where('duree_partie', '>=', $request->duree); // c'est un string dans l'énoncé...
            }
            if ($request->nb_joueurs_min != null) {
                $jeux = $jeux->where('nombre_joueurs_min', '>=', $request->nb_joueurs_min);
            }
            if ($request->nb_joueurs_max != null) {
                $jeux = $jeux->where('nombre_joueurs_max', '>=', $request->nb_joueurs_max);
            }
            if ($request->sort != null) {
                if ($request->sort == 'desc') {
                    $jeux = $jeux->sortByDesc('id');
                } else {
                    $jeux = $jeux->sortBy('id');
                }
            }

//            if ($request->editeur !=null) {
//                $jeux = $jeux->where($jeux->editeur, "=", $request->editeur);
//            }
//
//            if ($request->theme !=null) {
//                $jeux = $jeux->where($jeux->theme, "=", $request->theme);
//            }
//
//            if ($request->categorie !=null) {
//                $jeux = $jeux->where($jeux->categorie, '=', $request->categorie);
//            }

            // a continuer avec categorie, theme, editeur

            return response()->json([
                'status' => 'success',
                'Jeux' => new JeuResource($jeux),
            ], 200);
        } else {
            $jeux = Jeu::all()->random(5);

            return response()->json([
                'status' => 'success',
                'Jeux' => new JeuResource($jeux),
            ], 200);
            // si marche pas, juste return new JeuRessource
        }
    }

    /**
     * @OA\Post(
     *      path="/jeux/{id}",
     *      tags={"Jeu"},
     *      summary="Modifies the avatar url.",
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(),
     *
     *            @OA\Schema(
     *               type="object",
     *               required={"url_media"},
     *
     *               @OA\Property(property="url_media", type="string")
     *            ),
     *    ),
     *
     *          @OA\Response(
     *              response="200", description="Game url media updated successfully"
     *          ),
     *          @OA\Response(response="422", description="Unprocessable content")
     *  )
     */
    public function modifUrl(Request $request, $id)
    {
        try {
            $jeu = Jeu::findOrFail($id);
            $jeu->url_media = $request->url_media;
            $jeu->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Game url media updated successfully',
            ]);
        } catch (Exception) {
            return response()->json([
                'status' => false,
                'message' => 'Game url media not updated',
                'url_media' => $jeu->url_media,
            ]);
        }
    }

    /**
     * @OA\Post(
     *      path="/jeux/{id}",
     *      tags={"Jeu"},
     *      summary="Buy a game",
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(),
     *
     *            @OA\Schema(
     *               type="object",
     *               required={"date_achat","lieu_achat","prix","adherent_id","jeu_id"},
     *
     *               @OA\Property(property="date_achat", type="date"),
     *               @OA\Property(property="lieu_achat", type="string"),
     *               @OA\Property(property="prix", type="integer"),
     *               @OA\Property(property="adherent_id", type="integer"),
     *               @OA\Property(property="jeu_id", type="integer")
     *            ),
     *    ),
     *
     *          @OA\Response(
     *              response="200", description="Purchase created successfully",
     *
     *              @OA\JsonContent(
     *              type="array",
     *
     *              @OA\Items(ref="#/components/schemas/Achat")
     *              ),
     *
     *              @OA\JsonContent(
     *              type="array",
     *
     *              @OA\Items(ref="#/components/schemas/Adherents")
     *              ),
     *
     *              @OA\JsonContent(
     *              type="array",
     *
     *              @OA\Items(ref="#/components/schemas/Jeu")
     *              )
     *          ),
     *
     *          @OA\Response(response="422", description="Unprocessable content")
     *  )
     */
    public function achatJeu(AchatRequest $request)
    {
        $jeu = Jeu::findOrFail($request->jeu_id);
        $adherent = auth()->user();
        if ($jeu->valide) {
            $achat = new Achat;
            $achat->date_achat = $request->date_achat;
            $achat->lieu_achat = $request->lieu_achat;
            $achat->prix = $request->prix;
            $achat->adherent_id = $adherent->id;
            $achat->jeu_id = $request->jeu_id;
            $achat->save();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Purchase created successfully',
            'achat' => $achat,
            'adherent' => $adherent,
            'jeu' => $jeu,
        ]);
    }

    /**
     * @OA\Get(
     *      path="/jeux/{id}",
     *      tags={"Jeu"},
     *      summary="Informations of the game",
     *
     *          @OA\Response(
     *              response="200", description="Full info of game",
     *
     *              @OA\JsonContent(
     *              type="array",
     *
     *              @OA\Items(ref="#/components/schemas/Achat")
     *              ),
     *
     *              @OA\JsonContent(
     *              type="array",
     *
     *              @OA\Items(ref="#/components/schemas/Commentary")
     *              ),
     *
     *              @OA\JsonContent(
     *              type="array",
     *
     *              @OA\Items(ref="#/components/schemas/Jeu")
     *              )
     *          ),
     *
     *          @OA\Response(response="422", description="Unprocessable content")
     *  )
     */
    public function detailJeu($idJeu)
    {
        $jeu = Jeu::findOrFail($idJeu);

        return response()->json([
            'status' => 'success',
            'message' => 'Full info of game',
            'achats' => $jeu->achats,
            'commentaires' => $jeu->commentaires,
            'jeu' => $jeu,
            'nb_likes' => $jeu->adherents,
        ]);
    }

    /**
     * @OA\Delete(
     *      path="/achat/{id}",
     *      tags={"Jeu"},
     *      summary="Deletes a purchase",
     *
     *          @OA\Response(
     *              response="200", description="Achat successfully deleted",
     *          ),
     *          @OA\Response(response="422", description="Unprocessable content")
     *  )
     */
    public function supprimerAchat($idJeu)
    {
        $user = auth()->user();

        $jeu = Jeu::findOrFail($idJeu);
//        Achat::where([['adherent_id', "=", $user->id], ["jeu_id", "=", $idJeu]])->first()->delete();
        Achat::destroy([$user->id, $idJeu]);
        if (Gate::denies('same-user', $user)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Achat successfully deleted',
        ]);
    }
}
