<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleName;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        User::factory()->create([
            'name' => 'Tom Admin',
            'email' => 'tom.admin@example.com',
            'role_id' => Role::where('name', RoleName::ADMIN)->first()->id,
        ]);

        User::factory()->create([
            'name' => 'John Manager',
            'email' => 'john.manager@example.com',
            'role_id' => Role::where('name', RoleName::MANAGER)->first()->id,
        ]);

        User::factory()->create([
            'name' => 'Bob User',
            'email' => 'bob.user@example.com',
            'role_id' => Role::where('name', RoleName::USER)->first()->id,
        ]);
    }

    public function isDataAlreadyGiven(): bool
    {
        return User::count() > 0;
    }
}
