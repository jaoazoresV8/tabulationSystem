<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Score extends Model
{
    protected $fillable = [
        'judge_id',
        'contestant_id',
        'criterion_id',
        'exposure_id',
        'score',
        'comment',
        'is_final',
        'ballot_hash',
        'change_count',
    ];

    public function judge(): BelongsTo
    {
        return $this->belongsTo(Judge::class);
    }

    public function contestant(): BelongsTo
    {
        return $this->belongsTo(Contestant::class);
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(Criterion::class);
    }

    public function exposure(): BelongsTo
    {
        return $this->belongsTo(Exposure::class);
    }
}
