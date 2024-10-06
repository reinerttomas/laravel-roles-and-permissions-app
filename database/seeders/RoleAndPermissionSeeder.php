<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Enums\PermissionEnum;
use App\Enums\RoleEnum;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RoleAndPermissionSeeder extends Seeder
{
    public function run(): void
    {
        foreach (PermissionEnum::cases() as $permission) {
            Permission::create(['name' => $permission->value]);
        }

        foreach (RoleEnum::cases() as $role) {
            $role = Role::create(['name' => $role->value]);

            $this->syncPermissionsToRole($role);
        }
    }

    private function syncPermissionsToRole(Role $role): void
    {
        $permissions = match ($role->name) {
            RoleEnum::MasterAdmin->value => $this->masterAdmin(),
            RoleEnum::ClinicOwner->value => $this->clinicOwner(),
            RoleEnum::ClinicAdmin->value => $this->clinicAdmin(),
            RoleEnum::Staff->value => $this->staff(),
            RoleEnum::Doctor->value => $this->doctor(),
            RoleEnum::Patient->value => $this->patient(),
            default => throw new \Exception('Unknown role: ' . $role->name),
        };

        $role->syncPermissions($permissions);
    }

    /**
     * @return list<RoleEnum>
     */
    private function masterAdmin(): array
    {
        return [
            PermissionEnum::ListTeam,
            PermissionEnum::CreateTeam,
        ];
    }

    /**
     * @return list<RoleEnum>
     */
    private function clinicOwner(): array
    {
        return [
            PermissionEnum::SwitchTeam,
            PermissionEnum::ListUser,
            PermissionEnum::CreateUser,
        ];
    }

    /**
     * @return list<RoleEnum>
     */
    private function clinicAdmin(): array
    {
        return [
            PermissionEnum::ListUser,
            PermissionEnum::CreateUser,
            PermissionEnum::ListTask,
            PermissionEnum::CreateTask,
            PermissionEnum::EditTask,
            PermissionEnum::DeleteTask,
        ];
    }

    /**
     * @return list<RoleEnum>
     */
    private function staff(): array
    {
        return [
            PermissionEnum::ListTask,
            PermissionEnum::CreateTask,
            PermissionEnum::EditTask,
            PermissionEnum::DeleteTask,
        ];
    }

    /**
     * @return list<RoleEnum>
     */
    private function doctor(): array
    {
        return [
            PermissionEnum::ListTask,
            PermissionEnum::CreateTask,
            PermissionEnum::EditTask,
        ];
    }

    /**
     * @return list<RoleEnum>
     */
    private function patient(): array
    {
        return [
            PermissionEnum::ListTask,
        ];
    }
}
