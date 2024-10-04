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

        $user1 = User::factory()->create([
            'name' => 'Tom Admin',
            'email' => 'tom.admin@example.com',
        ]);

        $user1->roles()->attach(Role::where('name', RoleName::ADMIN)->firstOrFail()->id);

        $user2 = User::factory()->create([
            'name' => 'John Manager',
            'email' => 'john.manager@example.com',
        ]);

        $user2->roles()->attach(Role::where('name', RoleName::MANAGER)->firstOrFail()->id);

        $user3 = User::factory()->create([
            'name' => 'Bob User',
            'email' => 'bob.user@example.com',
        ]);

        $user3->roles()->attach(Role::where('name', RoleName::USER)->firstOrFail()->id);
    }

    public function isDataAlreadyGiven(): bool
    {
        return User::count() > 0;
    }
}
