<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'role',
        'status',
        'configuration'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'configuration' => 'array'
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($user) {
            $user->uuid = (string) Str::uuid();
        });
    }

    public function isAdministrator(): bool
    {
        return $this->role === 1;
    }

    protected function library(): HasMany
    {
        return $this->hasMany(Library::class);
    }

    public function getConfiguration(string $key): ?string
    {
        $decoded = json_decode($this->attributes['preferences']);

        return $decoded->$key ?? null;
    }

    public function preferences(): ?Attribute
    {
        return new Attribute(
            static fn ($value) => json_decode($value),
        );
    }
}
