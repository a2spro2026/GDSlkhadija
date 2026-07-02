<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fournisseur extends Model
{
    protected $fillable = [
        'raison_sociale',
        'nom_responsable',
        'profil',
        'contact',
        'email',
    ];
}
