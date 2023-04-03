<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdherentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'login' => "required|string",
            'email' => "required|string",
            'password' => "required|string",
            'nom' => "required|string",
            'prenom' => "required|string",
            'pseudo' => "required|string"
        ];
    }
}
