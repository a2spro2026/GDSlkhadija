<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonCommande extends Model
{
    protected $table = 'bons_commandes';

    protected $fillable = [
        'date_bon',
        'numero_bon',
        'client',
        'ville',
        'adresse',
        'montant',
        'statut',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'date_bon' => 'date',
            'montant' => 'decimal:2',
        ];
    }

    public static function statutLabels(): array
    {
        return [
            'livre' => 'Livré',
            'en_attente' => 'En Attente',
            'annule' => 'Annulé',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
