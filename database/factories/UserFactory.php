<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\RoleEnum;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
final class UserFactory extends Factory
{
    protected static ?string $password;

    private string $clinicDefaultName = 'Clinic 123';

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => self::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): self
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function masterAdmin(): self
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::create([
                'name' => 'Master Admin Team',
            ]);

            $this->withRole(
                user: $user,
                role: RoleEnum::MasterAdmin,
                team: $team,
            );
        });
    }

    public function clinicOwner(): self
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::create([
                'name' => $this->clinicDefaultName,
            ]);

            $this->withRole(
                user: $user,
                role: RoleEnum::ClinicOwner,
                team: $team,
            );
        });
    }

    public function clinicAdmin(): self
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::firstOrCreate([
                'name' => $this->clinicDefaultName,
            ]);

            $this->withRole(
                user: $user,
                role: RoleEnum::ClinicAdmin,
                team: $team,
            );
        });
    }

    public function doctor(): self
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::firstOrCreate([
                'name' => $this->clinicDefaultName,
            ]);

            $this->withRole(
                user: $user,
                role: RoleEnum::Doctor,
                team: $team,
            );
        });
    }

    public function staff(): self
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::firstOrCreate([
                'name' => $this->clinicDefaultName,
            ]);

            $this->withRole(
                user: $user,
                role: RoleEnum::Staff,
                team: $team,
            );
        });
    }

    public function patient(): self
    {
        return $this->afterCreating(function (User $user) {
            $team = Team::firstOrCreate([
                'name' => $this->clinicDefaultName,
            ]);

            $this->withRole(
                user: $user,
                role: RoleEnum::Patient,
                team: $team,
            );
        });
    }

    private function withRole(User $user, RoleEnum $role, Team $team): void
    {
        $user->update(['current_team_id' => $team->id]);

        setPermissionsTeamId($team->id);

        $user->assignRole($role);
    }
}
