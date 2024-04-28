<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preset extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid',
        'source',
        'visibility',
        'status',
        'title',
        'description',
        'template',
        'icon',
        'color',
        'user_id',
        'category_id',
        'usage_count',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function libraries()
    {
        return $this->hasMany(Library::class, 'resource_id', 'id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($preset) {
            $preset->uuid = (string) Str::uuid();
        });
    }

    public function getAbbreviationAttribute()
    {
        $abbreviation = preg_replace('/\b(\w)\w*\s*/', '$1', $this->attributes['title']);
        return strtoupper(substr($abbreviation, 0, 2));
    }
}
