<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Role;
use Illuminate\Database\Seeder;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        Role::create(['name' => RoleName::USER]);
        Role::create(['name' => RoleName::ADMIN]);
        Role::create(['name' => RoleName::MANAGER]);
    }

    public function isDataAlreadyGiven(): bool
    {
        return Role::count() > 0;
    }
}
