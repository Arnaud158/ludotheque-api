<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdherentRequest;
use App\Models\Adherent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use OpenApi\Annotations as OA;

/**
 *  @OA\OpenApi(
 *
 *      @OA\Info(
 *          version="1.0.0",
 *          title="Serveur API Documentation",
 *          description="Serveur API Documentation.",
 *
 *          @OA\Contact(
 *              name="Elsa Logier & Arnaud Fievet & Jules Bobeuf & Thomas Santoro",
 *              email="thomas_santoro@ens.univ-artois.fr"
 *          ),
 *
 *          @OA\License(
 *              name="Apache 2.0",
 *              url="http://www.apache.org/licenses/LICENSE-2.0.html"
 *          )
 *      )
 *  )
 *
 *  @OA\Server(
 *      url="/api",
 *      description="OpenApi host"
 *  )
 *
 *  @OA\Schema(
 *      schema="Adherent",
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
 *
 *  @OA\Schema(
 *      schema="Commentaire",
 *      type="object",
 *      required={"id","commentaire","date_com", "note", "etat"},
 *
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="commentaire", type="string"),
 *      @OA\Property(property="date_com", type="date"),
 *      @OA\Property(property="note", type="int"),
 *      @OA\Property(property="etat", type="string")
 * )
 *
 *  @OA\Schema(
 *      schema="Achat",
 *      type="object",
 *      required={"id","date_achat","lieu_achat", "prix"},
 *
 *      @OA\Property(property="id", type="int"),
 *      @OA\Property(property="date_achat", type="string"),
 *      @OA\Property(property="lieu_achat", type="string"),
 *      @OA\Property(property="prix", type="int")
 * )
 */
class AdherentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     * path="/api/Register",
     * operationId="authRegister",
     * tags={"Adherent"},
     * summary="User Register",
     * description="Register User Here",
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(),
     *
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email","password","nom","prenom","pseudo"},
     *
     *               @OA\Property(property="name", type="string"),
     *               @OA\Property(property="email", type="string"),
     *               @OA\Property(property="password", type="string"),
     *               @OA\Property(property="nom", type="string"),
     *               @OA\Property(property="prenom", type="string"),
     *               @OA\Property(property="pseudo", type="string")
     *            ),
     *      ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Adherent created successfully",
     *
     *          @OA\JsonContent(
     *            type="array",
     *
     *            @OA\Items(ref="#/components/schemas/Adherent")
     *         )
     *      ),
     * )
     */
    public function register(AdherentRequest $request)
    {
        $adherent = new Adherent;
        $adherent->name = $request->login;
        $adherent->email = $request->email;
        $adherent->password = Hash::make($request->password);
        $adherent->nom = $request->nom;
        $adherent->prenom = $request->prenom;
        $adherent->pseudo = $request->pseudo;

        $adherent->save();
        $adherent->roles()->attach([4]);

        $adherent->save();

        $token = auth()->login($adherent);

        return response()->json([
            'status' => 'success',
            'message' => 'Adherent created successfully',
            'adherent' => $adherent,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ], 200);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * operationId="authLogin",
     * tags={"Adherent"},
     * summary="User Login",
     * description="Login User Here",
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(),
     *
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *
     *               @OA\Property(property="email", type="string"),
     *               @OA\Property(property="password", type="string")
     *            ),
     *    ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Adherent logged successfully",
     *
     *          @OA\JsonContent(
     *            type="array",
     *
     *            @OA\Items(ref="#/components/schemas/Adherent")
     *         )
     *       ),
     *
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *       )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
        $credentials = $request->only('email', 'password');

        $token = auth()->attempt($credentials);
        if (! $token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $adherent = auth()->user();

        return response()->json([
            'status' => 'success',
            'message' => 'Adherent logged successfully',
            'adherent' => $adherent,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ],
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/logout",
     * operationId="Logout",
     * tags={"Adherent"},
     * summary="User Logout",
     * description="User Logout here",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successfully logged out",
     *      )
     *   )
     */
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    /**
     * @OA\Get(
     * path="/api/info",
     * operationId="Info",
     * tags={"Adherent"},
     * summary="User info",
     * description="User info here",
     *
     *      @OA\Response(
     *          response=200,
     *          description="Successfully logged out",
     *
     *          @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/Adherent")
     *         ),
     *
     *          @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/Commentaire")
     *         ),
     *
     *          @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(ref="#/components/schemas/Achat")
     *         )
     *      ),
     *
     *      @OA\Response(
     *          response=403,
     *          description="Unauthorized",
     *      )
     *   )
     */
    public function info($id)
    {
        $userAsked = Adherent::findOrFail($id);
        if (Gate::allows('same-user', $userAsked)) {
            return response()->json([
                'status' => 'success',
                'message' => 'Successfully profil info',
                'adherent' => $userAsked,
                'commentaires' => $userAsked->commentaires(),
                'achats' => $userAsked->achats(),
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Unauthorized',
        ], 403);
    }

    /**
     * @OA\Post(
     * path="/api/Edit",
     * operationId="authEdit",
     * tags={"Adherent"},
     * summary="User Edit",
     * description="Edit User Here",
     *
     *     @OA\RequestBody(
     *
     *         @OA\JsonContent(),
     *
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email","password","nom","prenom","pseudo"},
     *
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="nom", type="text"),
     *               @OA\Property(property="prenom", type="text"),
     *               @OA\Property(property="pseudo", type="text")
     *            ),
     *    ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Adherent updated successfully",
     *
     *          @OA\JsonContent(
     *            type="array",
     *
     *            @OA\Items(ref="#/components/schemas/Adherent")
     *         )
     *       )
     * )
     */
    public function edit(AdherentRequest $request, $id)
    {
        $userAsked = Adherent::findOrFail($id);
        if (Gate::denies('same-user', $userAsked)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }
        $userAsked->name = $request->login;
        $userAsked->email = $request->email;
        $userAsked->password = Hash::make($request->password);
        $userAsked->nom = $request->nom;
        $userAsked->prenom = $request->prenom;
        $userAsked->pseudo = $request->pseudo;
        $userAsked->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Adherent updated successfully',
            'salle' => $userAsked,
        ], 200);
    }

    /**
     * @OA\Post(
     * path="/api/Avatar",
     * operationId="authAvatar",
     * tags={"Adherent"},
     * summary="User Avatar",
     * description="Avatar User Here",
     *
     *     @OA\RequestBody(
     *
     *     @OA\Property(property="avatar", type="string"),
     *    ),
     *
     *      @OA\Response(
     *          response=200,
     *          description="Adherent avatar updated successfully",
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Adherent avatar updated successfully",
     *       )
     * )
     */
    public function avatar(Request $request, $id)
    {
        $userAsked = Adherent::findOrFail($id);
        if (Gate::denies('same-user', $userAsked)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 403);
        }

        if (! $request->hasFile('avatar')) {
            return response()->json([
                'status' => 'error',
                'message' => 'avatar not send',
            ], 422);
        }

        $file = $request->file('avatar');

        if (! $file->isValid()) {
            return response()->json([
                'status' => 'error',
                'message' => 'upload failed',
            ], 422);
        }

        $nom = 'avatar';
        $now = time();
        $nom = sprintf('%s_%d.%s', $nom, $now, $file->extension());

        $file->storeAs('avatars', $nom);

        if ($userAsked->avatar != 'avatars/user-default.svg') {
            Storage::delete($userAsked->avatar);
        }
        $userAsked->avatar = 'avatars/' . $nom;
        $userAsked->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Adherent avatar updated successfully',
            'url_media' => $userAsked->avatar,
        ], 200);
    }
}
