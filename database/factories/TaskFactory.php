<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
final class TaskFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->text(30),
            'due_date' => fake()->dateTimeBetween('-1 week', '+1 week'),
            'assigned_to_user_id' => fake()->boolean() ? User::factory()->doctor() : User::factory()->staff(),
            'patient_id' => User::factory()->patient(),
        ];
    }
}
