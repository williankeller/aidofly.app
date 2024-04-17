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
        'model',
        'cost',
        'params',
        'title',
        'content',
        'user_id',
        'category_id',
        'preset_id',
    ];

    protected $casts = [
        'params' => 'array',
    ];

    // Define the relationship with User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Define the relationship with Category
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // Define the relationship with Preset
    public function preset()
    {
        return $this->belongsTo(Preset::class, 'preset_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = (string) Str::uuid();
        });
    }
}
