<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jeu extends Model
{
    use HasFactory;
    public $timestamps = false;

    public function likes() {
        return $this->belongsToMany(Adherent::class);
    }

    public function commentaires() {
        return $this->belongsToMany(Commentaire::class);
    }

    public function achats() {
        return $this->belongsToMany(Adherent::class,'achats')
            ->as('achats')
            ->withPivot('date_achat','lieu_achat','prix');
    }

    public function theme() {
        return $this->hasOne('themes');
    }

    public function editeur() {
        return $this->hasOne('editeurs');
    }

    public function categories() {
        return $this->hasMany('categories');
    }
}
