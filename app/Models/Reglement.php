<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Reglement extends Model
{
    protected $fillable = [
        'reference',
        'date_reglement',
        'fournisseur_id',
        'type_reglement',
        'numero',
        'banque',
        'montant',
        'nom_tire',
        'date_decaissement',
        'statut',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'date_reglement' => 'date',
            'date_decaissement' => 'date',
            'montant' => 'decimal:2',
        ];
    }

    public static function typeLabels(): array
    {
        return [
            'esp' => 'Esp',
            'chq' => 'Chq',
            'eff' => 'Eff',
            'vir' => 'Vir',
            'vers' => 'Vers',
        ];
    }

    public static function statutLabels(): array
    {
        return [
            'paye' => 'Payé',
            'impaye' => 'Impayé',
            'reporte' => 'Reporté',
            'devalide' => 'Dévalidé',
        ];
    }

    public function fournisseur(): BelongsTo
    {
        return $this->belongsTo(Fournisseur::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bonsAchats(): BelongsToMany
    {
        return $this->belongsToMany(BonAchat::class, 'reglement_bon_achat')
            ->withPivot('montant_affecte')
            ->withTimestamps();
    }

    public function isVerrouille(): bool
    {
        return $this->statut === 'paye';
    }
}
