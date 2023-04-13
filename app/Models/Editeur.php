<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Editeur extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $guarded = [];

    public function adherents()
    {
        return $this->hasMany(Adherent::class);
    }
}
