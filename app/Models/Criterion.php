<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Criterion extends Model
{
    protected $fillable = [
        'exposure_id',
        'name',
        'percentage',
        'min_score',
        'max_score',
    ];

    public function exposure(): BelongsTo
    {
        return $this->belongsTo(Exposure::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
