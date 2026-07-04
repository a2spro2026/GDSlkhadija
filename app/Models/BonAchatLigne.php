<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BonAchatLigne extends Model
{
    protected $fillable = [
        'bon_achat_id',
        'reference',
        'designation',
        'quantite',
        'prix_unitaire',
        'sous_total',
    ];

    protected function casts(): array
    {
        return [
            'quantite' => 'decimal:2',
            'prix_unitaire' => 'decimal:2',
            'sous_total' => 'decimal:2',
        ];
    }

    public function bonAchat(): BelongsTo
    {
        return $this->belongsTo(BonAchat::class);
    }
}
