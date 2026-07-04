<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BonAchat extends Model
{
    protected $table = 'bons_achats';

    protected $fillable = [
        'date_bon',
        'numero_bon',
        'fournisseur_id',
        'total',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'date_bon' => 'date',
            'total' => 'decimal:2',
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

    public function lignes(): HasMany
    {
        return $this->hasMany(BonAchatLigne::class);
    }

    public function reglements(): BelongsToMany
    {
        return $this->belongsToMany(Reglement::class, 'reglement_bon_achat')
            ->withPivot('montant_affecte')
            ->withTimestamps();
    }

    public function montantPaye(): float
    {
        if ($this->relationLoaded('reglements')) {
            return (float) $this->reglements
                ->where('statut', 'paye')
                ->sum(fn ($reglement) => (float) $reglement->pivot->montant_affecte);
        }

        return (float) $this->reglements()
            ->where('statut', 'paye')
            ->sum('reglement_bon_achat.montant_affecte');
    }

    public function montantImpaye(): float
    {
        if ($this->relationLoaded('reglements')) {
            return (float) $this->reglements
                ->where('statut', 'impaye')
                ->sum(fn ($reglement) => (float) $reglement->pivot->montant_affecte);
        }

        return (float) $this->reglements()
            ->where('statut', 'impaye')
            ->sum('reglement_bon_achat.montant_affecte');
    }

    public function solde(): float
    {
        return max(0, round((float) $this->total - $this->montantPaye(), 2));
    }

    public function statutBalance(): string
    {
        return $this->solde() <= 0 ? 'paye' : 'impaye';
    }
}
