<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdherentRequest;
use App\Models\Adherent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class AdherentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * @OA\Post(
     * path="/api/register",
     * operationId="Register",
     * tags={"Register"},
     * summary="User Register",
     * description="User Register here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"name","email", "password", "nom", "prenom", "pseudo"},
     *               @OA\Property(property="name", type="text"),
     *               @OA\Property(property="email", type="text"),
     *               @OA\Property(property="password", type="password"),
     *               @OA\Property(property="password_confirmation", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Register Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function register(AdherentRequest $request)
    {
        $adherent = new Adherent();
        $adherent->name = $request->login;
        $adherent->email = $request->email;
        $adherent->password = Hash::make($request->password);
        $adherent->nom = $request->nom;
        $adherent->prenom = $request->prenom;
        $adherent->pseudo = $request->pseudo;

        $adherent->save();
        $adherent->roles()->attach([4]);

        $adherent->save();

        $token = Auth::login($adherent);
        return response()->json([
            "status" => "success",
            "message" => "Adherent created successfully",
            "adherent" => $adherent,
            "authorisation" => [
                'token' => $token,
                'type' => 'bearer'
            ]
        ], 200);
    }

    /**
     * @OA\Post(
     * path="/api/login",
     * operationId="authLogin",
     * tags={"Login"},
     * summary="User Login",
     * description="Login User Here",
     *     @OA\RequestBody(
     *         @OA\JsonContent(),
     *         @OA\MediaType(
     *            mediaType="multipart/form-data",
     *            @OA\Schema(
     *               type="object",
     *               required={"email", "password"},
     *               @OA\Property(property="email", type="email"),
     *               @OA\Property(property="password", type="password")
     *            ),
     *        ),
     *    ),
     *      @OA\Response(
     *          response=201,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=200,
     *          description="Login Successfully",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity",
     *          @OA\JsonContent()
     *       ),
     *      @OA\Response(response=400, description="Bad request"),
     *      @OA\Response(response=404, description="Resource Not Found"),
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            ]);
        $credentials = $request->only('email', 'password');

        $token = Auth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }

        $adherent = Auth::user();
        return response()->json([
            'status' => 'success',
            'message' => 'Adherent logged successfully',
            'adherent' => $adherent,
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer'
                ]
        ]);
    }

    public function logout() {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);
    }

    public function info($id) {
        $userAsked = Adherent::findOrFail($id);
        if (Gate::allows('same-user', $userAsked)) {
            return response()->json([
                'status' => "success",
                'message' => 'Successfully profil info',
                'adherent' => $userAsked,
                'commentaires' => $userAsked->commentaires(),
                'achats' => $userAsked->achats()
        ], 200);
        }
        return response()->json([
            'status' => "error",
            'message' => 'Unauthorized',
        ], 403);
    }

    public function edit(AdherentRequest $request, $id) {
        $userAsked = Adherent::findOrFail($id);
        if (Gate::denies('same-user', $userAsked)) {
            return response()->json([
            'status' => "error",
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
            'status' => "success",
            'message' => 'Adherent updated successfully',
            'salle' => $userAsked
        ], 200);
    }

    public function avatar(Request $request, $id) {
        $userAsked = Adherent::findOrFail($id);
        if (Gate::denies('same-user', $userAsked)) {
            return response()->json([
            'status' => "error",
            'message' => 'Unauthorized',
        ], 403);
        }

        if (!$request->hasFile("avatar")) {
            return response()->json([
            'status' => "error",
            'message' => 'avatar not send'
        ], 422);
        }

        $file = $request->file('avatar');

        if (!$file->isValid()) {
            return response()->json([
            'status' => "error",
            'message' => 'upload failed'
        ], 422);
        }

        $nom = 'avatar';
        $now = time();
        $nom = sprintf("%s_%d.%s", $nom, $now, $file->extension());

        $file->storeAs('avatars', $nom);

        if ($userAsked->avatar != 'avatars/user-default.svg') {
            Storage::delete($userAsked->avatar);
        }
        $userAsked->avatar = 'avatars/'.$nom;
        $userAsked->save();

        return response()->json([
            'status' => "success",
            'message' => 'Adherent avatar updated successfully',
            "url_media"=> $userAsked->avatar
        ], 200);

    }

}
