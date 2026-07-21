<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Judge extends Model
{
    protected $fillable = [
        'contest_id',
        'name',
        'access_code',
        'is_online',
        'last_activity',
    ];

    protected function casts(): array
    {
        return [
            'is_online' => 'boolean',
            'last_activity' => 'datetime',
        ];
    }

    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
