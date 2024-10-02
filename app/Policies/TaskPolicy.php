<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

final readonly class TaskPolicy
{
    public function create(): bool
    {
        return true;
    }

    public function update(User $user, Task $task): bool
    {
        return $user->is_admin || $task->user_id === $user->id;
    }

    public function delete(User $user, Task $task): bool
    {
        return $user->is_admin || $task->user_id === $user->id;
    }
}
