<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
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
        ];
    }

    public function isMarketing(): bool
    {
        return $this->role === 'cio_marketing';
    }

    public function isProduction(): bool
    {
        return $this->role === 'cio_production';
    }

    public function roleLabel(): string
    {
        return match ($this->role) {
            'cio_marketing' => 'CIO Marketing',
            'cio_production' => 'CIO Production',
            default => $this->role,
        };
    }
}
