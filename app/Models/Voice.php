<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Voice extends Model
{
    use HasFactory;

    protected $fillable = [
        'provider',
        'model',
        'status',
        'sample',
        'token',
        'name',
        'gender',
        'case',
        'accent',
        'age',
        'tone',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = (string) Str::uuid();
        });
    }

    public function libraries()
    {
        return $this->hasMany(Library::class, 'resource_id', 'id');
    }

    /**
     * Return the gender in its human readable form 1: Male, 2: Female
     * @return string
     */
    public function getGenderAttribute(): string
    {
        return match ($this->attributes['gender']) {
            1 => __('male'),
            2 => __('female'),
            default => __('unknown'),
        };
    }
}
