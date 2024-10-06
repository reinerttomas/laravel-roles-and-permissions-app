<?php

declare(strict_types=1);

use App\Enums\RoleEnum;
use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

it('allows clinic owner and admin to view users list', function (): void {
    $clinicOwner = User::factory()->clinicOwner()->create();
    $clinicAdmin = User::factory()->clinicAdmin()->create();

    $doctor = User::factory()->doctor()->create();
    $staff = User::factory()->staff()->create();

    $patient = User::factory()->patient()->create();

    actingAs($clinicOwner)
        ->get(route('users.index'))
        ->assertOk()
        ->assertViewHas('users', fn (Collection $users): bool => $users->contains(fn (User $user): bool => $user->name === $clinicAdmin->name
                || $user->name === $doctor->name
                || $user->name === $staff->name
        ) && $users->doesntContain(fn (User $user): bool => $user->name === $patient->name));

    actingAs($clinicAdmin)
        ->get(route('users.index'))
        ->assertOk()
        ->assertViewHas('users', fn (Collection $users): bool => $users->contains(fn (User $user): bool => $user->name === $clinicAdmin->name
                || $user->name === $doctor->name
                || $user->name === $staff->name
        ) && $users->doesntContain(fn (User $user): bool => $user->name === $patient->name));
});

it('forbids users without access to enter users list page', function (User $user): void {
    actingAs($user)
        ->get(route('users.index'))
        ->assertForbidden();
})->with([
    fn () => User::factory()->masterAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('forbids users without access to enter create user page', function (User $user): void {
    actingAs($user)
        ->get(route('users.create'))
        ->assertForbidden();
})->with([
    fn () => User::factory()->masterAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('allows clinic owner to create a new user and assign a role', function (RoleEnum $role): void {
    $clinicOwner = User::factory()->clinicOwner()->create();

    actingAs($clinicOwner)
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new@user.com',
            'password' => 'password',
            'role_id' => Role::where('name', $role->value)->first()->id,
        ]);

    $newUser = User::where('email', 'new@user.com')->first();

    expect($newUser->hasRole($role))->toBeTrue();
})->with([
    RoleEnum::ClinicAdmin,
    RoleEnum::Doctor,
    RoleEnum::Staff,
]);

it('allows clinic admin to create a new user and assign a role', function (RoleEnum $role): void {
    $clinicAdmin = User::factory()->clinicAdmin()->create();

    actingAs($clinicAdmin)
        ->post(route('users.store'), [
            'name' => 'New User',
            'email' => 'new@user.com',
            'password' => 'password',
            'role_id' => Role::where('name', $role->value)->first()->id,
        ]);

    $newUser = User::where('email', 'new@user.com')->first();

    expect($newUser->hasRole($role))->toBeTrue();
})->with([
    RoleEnum::ClinicAdmin,
    RoleEnum::Doctor,
    RoleEnum::Staff,
]);
