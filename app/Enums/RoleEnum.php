<?php

declare(strict_types=1);

namespace App\Enums;

enum RoleEnum: string
{
    case Patient = 'patient';
    case Doctor = 'doctor';
    case Staff = 'staff';
    case ClinicAdmin = 'clinic-admin';
    case ClinicOwner = 'clinic-owner';
    case MasterAdmin = 'master-admin';
}
