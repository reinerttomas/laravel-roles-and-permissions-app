<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

final class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->count(3)->create();

        Task::factory()
            ->count(100)
            ->recycle(User::all())
            ->create();
    }
}
