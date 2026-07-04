<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Charge extends Model
{
    protected $fillable = [
        'date_charge',
        'libelle',
        'montant',
        'user_id',
    ];

    protected function casts(): array
    {
        return [
            'date_charge' => 'date',
            'montant' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
