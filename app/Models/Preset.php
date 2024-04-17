<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preset extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'category_id', 'type', 'visibility', 'status',
        'title', 'description', 'template', 'icon', 'color'
    ];

    // Relation to Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relation to Library
    public function libraries()
    {
        return $this->hasMany(Library::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($preset) {
            $preset->uuid = (string) Str::uuid();
        });
    }
}
