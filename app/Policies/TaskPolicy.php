<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Task;
use App\Models\User;

final class TaskPolicy
{
    public function create(User $user): bool
    {
        return $user->hasRole(RoleEnum::ADMIN);
    }

    public function update(User $user, Task $task): bool
    {
        if ($user->hasAnyRole([RoleEnum::ADMIN, RoleEnum::MANAGER])) {
            return true;
        }

        return $task->user_id === $user->id;
    }

    public function delete(User $user): bool
    {
        return $user->hasRole(RoleEnum::ADMIN);
    }
}
