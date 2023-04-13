<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jeu extends Model
{
    use HasFactory;

    public $timestamps = false;

    public function adherents() {
        return $this->belongsToMany(Adherent::class, 'like');
    }

    public function commentaires() {
        return $this->hasMany(Commentaire::class);
    }

    public function achats() {
        return $this->belongsToMany(Adherent::class,'achats')
            ->as('achats')
            ->withPivot('date_achat','lieu_achat','prix');
    }

    public function theme() {
        return $this->belongsTo(Theme::class);
    }

    public function editeur() {
        return $this->belongsTo(Editeur::class);
    }

    public function categorie() {
        return $this->hasMany(Categorie::class);
    }
}
