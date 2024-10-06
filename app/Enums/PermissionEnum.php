<?php

declare(strict_types=1);

namespace App\Enums;

enum PermissionEnum: string
{
    case ListTeam = 'list-team';
    case CreateTeam = 'create-team';
    case SwitchTeam = 'switch-team';

    case ListUser = 'list-user';
    case CreateUser = 'create-user';

    case ListTask = 'list-task';
    case CreateTask = 'create-task';
    case EditTask = 'edit-task';
    case DeleteTask = 'delete-task';
}
