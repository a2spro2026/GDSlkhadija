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

    public function isIam(): bool
    {
        return str_contains(mb_strtoupper($this->raison_sociale ?? ''), 'IAM');
    }

    public static function iamIds(): array
    {
        return static::query()
            ->get()
            ->filter(fn (self $f) => $f->isIam())
            ->pluck('id')
            ->all();
    }
}
