<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Database\Seeder;

final class UserSeeder extends Seeder
{
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        $user1 = User::factory()->create([
            'name' => 'Tom Admin',
            'email' => 'tom.admin@example.com',
        ]);

        $user1->assignRole(RoleEnum::ADMIN);

        $user2 = User::factory()->create([
            'name' => 'John Manager',
            'email' => 'john.manager@example.com',
        ]);

        $user2->assignRole(RoleEnum::USER);

        $user3 = User::factory()->create([
            'name' => 'Bob User',
            'email' => 'bob.user@example.com',
        ]);

        $user3->assignRole(RoleEnum::MANAGER);
    }

    public function isDataAlreadyGiven(): bool
    {
        return User::count() > 0;
    }
}
