<?php

declare(strict_types=1);

use App\Models\Team;
use App\Models\User;

use function Pest\Laravel\actingAs;

it('allows to create a new team and assign existing user', function () {
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

it('allows to create a new team with a new user', function () {
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
