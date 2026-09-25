<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_PRODUCAO = 'producao';

    public const ROLES = [
        self::ROLE_ADMIN => 'Administrador',
        self::ROLE_PRODUCAO => 'Produção',
    ];

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

    /**
     * Quem pode entrar no painel administrativo. Por enquanto, admin e produção
     * (o login das óticas, num portal separado, entra na Etapa 3).
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return in_array($this->role, [self::ROLE_ADMIN, self::ROLE_PRODUCAO], true);
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }
}
