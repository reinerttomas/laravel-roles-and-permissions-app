<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

final class RoleSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        Role::create(['name' => RoleEnum::ADMIN]);
        Role::create(['name' => RoleEnum::USER]);
        Role::create(['name' => RoleEnum::MANAGER]);
    }

    public function isDataAlreadyGiven(): bool
    {
        return Role::count() > 0;
    }
}
