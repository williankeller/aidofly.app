<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Library extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'visibility',
        'title',
        'params',
        'model',
        'content',
        'cost',
        'tokens',
        'user_id',
        'resource_id',
    ];

    protected $casts = [
        'params' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function voice(): BelongsTo
    {
        return $this->belongsTo(Voice::class, 'resource_id');
    }

    public function preset(): BelongsTo
    {
        return $this->belongsTo(Preset::class, 'resource_id');
    }

    public function getCostAttribute($value)
    {
        return formatNumber($value);
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = (string) Str::uuid();
        });
    }
}
