<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exposure extends Model
{
    protected $fillable = [
        'contest_id',
        'name',
        'type',
        'order',
        'weight',
        'top_n',
        'carry_over',
        'status',
        'previous_exposure_id',
    ];

    public function contest(): BelongsTo
    {
        return $this->belongsTo(Contest::class);
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(Criterion::class);
    }

    public function scores(): HasMany
    {
        return $this->hasMany(Score::class);
    }
}
