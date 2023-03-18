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

class AdherentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
    }

    /**
     * Create a user
     *
     * @param AdherentRequest $request
     * @return JsonResponse
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
        $userAsked->password = $request->password;
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

}
