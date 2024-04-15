<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Library extends Model
{
    use HasFactory;

    protected $table = 'library';

    protected $fillable = [
        'object',
        'visibility',
        'model',
        'cost',
        'params',
        'title',
        'content',
        'user_id'
    ];

    protected $casts = [
        'params' => 'array',
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = (string) Str::uuid();
        });
    }
}
