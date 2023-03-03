<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JeuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'nom' => "required|string|between:5,50",
            'description' => "required|string|between:5,50",
            'langue' => "required|integer|between:4,6",
            'age_min' => "required|string|between:2,50",
            'nombre_joueurs_min' => "required|string|between:2,50",
            'nombre_joueurs_max' => "required|string|between:2,50",
            'duree_partie' => "required|string|between:2,50",
            'categorie' => "required|string|between:2,50",
            'theme' => "required|string|between:2,50",
            'editeur' => "required|string|between:2,50"
        ];
    }
}
