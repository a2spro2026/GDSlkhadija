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

    public function bonsAchats()
    {
        return $this->hasMany(BonAchat::class);
    }

    public function reglements()
    {
        return $this->hasMany(Reglement::class);
    }

    public function soldeTotal(): float
    {
        return (float) $this->bonsAchats()
            ->get()
            ->sum(fn (BonAchat $bon) => $bon->solde());
    }
}
