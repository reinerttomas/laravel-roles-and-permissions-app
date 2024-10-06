<?php

declare(strict_types=1);

use App\Enums\RoleEnum;
use App\Models\Team;
use App\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

it('allows to create a new team and assign existing user', function (): void {
    $masterAdmin = User::factory()->masterAdmin()->create();
    $clinicOwner = User::factory()->clinicOwner()->create();

    actingAs($masterAdmin)
        ->post(route('teams.store'), [
            'clinic_name' => 'New Team',
            'user_id' => $clinicOwner->id,
        ]);

    $newTeam = Team::where('name', 'New Team')->first();

    expect($clinicOwner->belongsToTeam($newTeam))->toBeTrue();
});

it('allows to create a new team with a new user', function (): void {
    $masterAdmin = User::factory()->masterAdmin()->create();

    actingAs($masterAdmin)
        ->post(route('teams.store'), [
            'clinic_name' => 'New Team',
            'name' => 'New User',
            'email' => 'new@user.com',
            'password' => 'password',
        ]);

    $newTeam = Team::where('name', 'New Team')->first();
    $newUser = User::where('email', 'new@user.com')->first();

    expect($newUser->belongsToTeam($newTeam))->toBeTrue();
});

it('allows clinic owner to change team', function (): void {
    $clinicOwner = User::factory()->clinicOwner()->create();
    $secondTeam = Team::factory()->create();

    $clinicOwner->teams()->attach($secondTeam->id, [
        'role_id' => Role::where('name', RoleEnum::ClinicOwner->value)->first()->id,
        'model_type' => $clinicOwner->getMorphClass(),
    ]);

    actingAs($clinicOwner)
        ->get(route('team.change', $secondTeam->id));

    expect($clinicOwner->refresh()->current_team_id)->toBe($secondTeam->id);
});

it('does not allow user to change team if user is not in the team', function (): void {
    $clinicOwner = User::factory()->clinicOwner()->create();
    $secondTeam = Team::factory()->create();

    actingAs($clinicOwner)
        ->get(route('team.change', $secondTeam->id))
        ->assertNotFound();

    expect($clinicOwner->refresh()->current_team_id)->toBe($clinicOwner->current_team_id);
});

it('does not allow to change team for user without switch team permissions', function (User $user): void {
    $team = Team::factory()->create();

    actingAs($user)
        ->get(route('team.change', $team->id))
        ->assertForbidden();
})->with([
    fn () => User::factory()->masterAdmin()->create(),
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->staff()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->patient()->create(),
]);
