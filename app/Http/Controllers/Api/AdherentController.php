<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdherentRequest;
use App\Models\Adherent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $adherant = new Adherent();
        $adherant->name = $request->login;
        $adherant->email = $request->email;
        $adherant->password = Hash::make($request->password);
        $adherant->nom = $request->nom;
        $adherant->prenom = $request->prenom;
        $adherant->pseudo = $request->pseudo;

        $adherant->save();

        $token = Auth::login($adherant);
        return response()->json([
            "status" => "success",
            "message" => "Adherent created successfully",
            "adherent" => $adherant,
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

}
