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

    protected $appends = [
        'abbreviation',
        'translated_title',
        'translated_description',
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

    public function getAbbreviationAttribute(): string
    {
        $title = (string) $this->attributes['title'];
        $abbreviation = preg_replace('/\b(\w)\w*\s*/u', '$1', $title);
        return strtoupper(mb_substr($abbreviation, 0, 2, 'UTF-8'));
    }

    public function getTranslatedTitleAttribute(): string
    {
        return __($this->attributes['title']);
    }

    public function getTranslatedDescriptionAttribute(): string
    {
        return __($this->attributes['description']);
    }
}
