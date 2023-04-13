<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentaireRequest extends FormRequest
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
            'commentaire' => 'required|string|between:1,200',
            'date_com' => 'required|date',
            'note' => 'required|integer',
            'jeu_id' => 'required|integer|exists:jeus,id',
            'adherent_id' => 'required|integer|exists:adherents,id',
        ];
    }
}
