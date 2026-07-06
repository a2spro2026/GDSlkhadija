<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DepotIamArticle extends Model
{
    public const STATUT_ACTIF = 'actif';

    public const STATUT_INACTIF = 'inactif';

    public const ETAT_DISPO = 'dispo';

    public const ETAT_FAIBLE = 'faible';

    public const ETAT_RUPTURE = 'rupture';

    public const STATUT_LABELS = [
        self::STATUT_ACTIF => 'Actif',
        self::STATUT_INACTIF => 'Inactif',
    ];

    public const ETAT_LABELS = [
        self::ETAT_DISPO => 'Dispo',
        self::ETAT_FAIBLE => 'Faible',
        self::ETAT_RUPTURE => 'Rupture',
    ];

    protected $fillable = [
        'reference',
        'designation',
        'mesure',
        'stock_initial',
        'entree',
        'sortie',
        'statut',
        'etat',
    ];

    protected function casts(): array
    {
        return [
            'stock_initial' => 'decimal:2',
            'entree' => 'decimal:2',
            'sortie' => 'decimal:2',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (DepotIamArticle $article) {
            $article->etat = $article->computeEtat();
        });
    }

    public function stockFinal(): float
    {
        return round((float) $this->stock_initial + (float) $this->entree - (float) $this->sortie, 2);
    }

    public function computeEtat(): string
    {
        $final = $this->stockFinal();

        if ($final <= 0) {
            return self::ETAT_RUPTURE;
        }

        $reference = (float) $this->stock_initial + (float) $this->entree;

        if ($reference > 0) {
            $seuil = max(1, $reference * 0.2);
            if ($final <= $seuil) {
                return self::ETAT_FAIBLE;
            }
        } elseif ($final <= 5) {
            return self::ETAT_FAIBLE;
        }

        return self::ETAT_DISPO;
    }

    public function statutLabel(): string
    {
        return self::STATUT_LABELS[$this->statut] ?? $this->statut;
    }

    public function etatLabel(): string
    {
        return self::ETAT_LABELS[$this->etat] ?? $this->etat;
    }
}
