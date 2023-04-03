<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AchatRequest extends FormRequest
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
            'date_achat' => "required|string|between:1,50",
            'lieu_achat' => "required|string|between:1,50",
            'prix' => "required|string|between:1,10",
            'adherent_id' => "required|number",
            'jeu_id' => "required|integer"
        ];
    }
}
