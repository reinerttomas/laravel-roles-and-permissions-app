<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

final class Role extends Model
{
    /** @use HasFactory<\Database\Factories\RoleFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    protected function casts(): array
    {
        return [
            'name' => RoleName::class,
        ];
    }

    public function isAdmin(): bool
    {
        return $this->name->isAdmin();
    }

    public function isUser(): bool
    {
        return $this->name->isUser();
    }

    public function isManager(): bool
    {
        return $this->name->isManager();
    }
}
