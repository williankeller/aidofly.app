<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function voice()
    {
        return $this->belongsTo(Voice::class, 'resource_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = (string) Str::uuid();
        });
    }
}
