<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'priority',
        'status',
        'assigned_to',
        'created_by',
        'client_name',
        'location',
        'due_date',
        'completed_at',
        'technician_notes',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(TaskMaterial::class);
    }

    public static function statusLabels(): array
    {
        return [
            'en_attente' => 'En attente',
            'en_cours' => 'En cours',
            'terminee' => 'Terminée',
            'annulee' => 'Annulée',
        ];
    }

    public static function priorityLabels(): array
    {
        return [
            'basse' => 'Basse',
            'normale' => 'Normale',
            'haute' => 'Haute',
            'urgente' => 'Urgente',
        ];
    }
}
