<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

final class TaskPolicy
{
    public function create(User $user): bool
    {
        return $user->role->isAdmin();
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->role->isAdmin() || $user->role->isManager()) {
            return true;
        }

        return $task->user_id === $user->id;
    }

    public function delete(User $user): bool
    {
        return $user->role->isAdmin();
    }
}
