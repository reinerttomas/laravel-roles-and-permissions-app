<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';
    case MANAGER = 'manager';

    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    public function isUser(): bool
    {
        return $this === self::USER;
    }

    public function isManager(): bool
    {
        return $this === self::MANAGER;
    }
}
