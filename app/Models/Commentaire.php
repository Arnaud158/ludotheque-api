<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commentaire extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function adherent()
    {
        return $this->belongsTo(Adherent::class);
    }
}
