<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

use Illuminate\Support\Str;

class Contest extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'type',
        'logo',
        'description',
        'tabulator_name',
        'status',
    ];

    protected static function booted()
    {
        static::creating(function ($contest) {
            if (empty($contest->uuid)) {
                $contest->uuid = (string) Str::uuid();
            }
        });
    }

    public function exposures(): HasMany
    {
        return $this->hasMany(Exposure::class)->orderBy('order');
    }

    public function contestants(): HasMany
    {
        return $this->hasMany(Contestant::class);
    }

    public function judges(): HasMany
    {
        return $this->hasMany(Judge::class);
    }
}
