<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contestant extends Model
{
    protected $fillable = [
        'contest_id',
        'number',
        'name',
        'performance_url',
        'team',
        'gender',
        'photo',
        'is_active',
    ];

    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
