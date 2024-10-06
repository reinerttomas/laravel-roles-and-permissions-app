<?php

declare(strict_types=1);

namespace App\Policies;

use App\Enums\PermissionEnum;
use App\Models\User;

final class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::ListTask);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::CreateTask);
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::EditTask);
    }

    public function delete(User $user): bool
    {
        return $user->hasPermissionTo(PermissionEnum::DeleteTask);
    }
}
