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
            'nom' => "required|string|between:1,50",
            'description' => "required|string|between:1,50",
            'langue' => "required|string|between:1,10",
            'age_min' => "required|number",
            'nombre_joueurs_min' => "required|integer",
            'nombre_joueurs_max' => "required|integer",
            'duree_partie' => "required|string|between:1,50",
            'categorie' => "required|string|between:1,50",
            'theme' => "required|string|between:1,50",
            'editeur' => "required|string|between:1,50"
        ];
    }
}
