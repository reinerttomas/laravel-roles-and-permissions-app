<?php

declare(strict_types=1);

use App\Models\Task;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Collection;

use function Pest\Laravel\actingAs;

it('allows clinic admin and staff to access create task page', function (User $user): void {
    actingAs($user)
        ->get(route('tasks.create'))
        ->assertOk();
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('does not allow patient to access create task page', function (): void {
    $user = User::factory()->patient()->create();

    actingAs($user)
        ->get(route('tasks.create'))
        ->assertForbidden();
});

it('allows clinic admin and staff to enter update page for any task in their team', function (User $user): void {
    $team = Team::first();

    $clinicAdmin = User::factory()->clinicAdmin()->create();
    $clinicAdmin->update(['current_team_id' => $team->id]);
    setPermissionsTeamId($team->id);
    $clinicAdmin->unsetRelation('roles')->unsetRelation('permissions');

    $task = Task::factory()->create([
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->get(route('tasks.edit', $task))
        ->assertOk();
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('does not allow administrator and manager to enter update page for other teams task', function (User $user): void {
    $team = Team::factory()->create();

    $task = Task::factory()->create([
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->get(route('tasks.edit', $task))
        ->assertNotFound();
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('allows administrator and manager to update any task in their team', function (User $user): void {
    $team = Team::first();

    $otherUser = User::factory()->clinicAdmin()->create();
    $otherUser->update(['current_team_id' => $team->id]);
    setPermissionsTeamId($team->id);
    $otherUser->unsetRelation('roles')->unsetRelation('permissions');

    $task = Task::factory()->create([
        'team_id' => $team->id,
    ]);

    actingAs($user)
        ->put(route('tasks.update', $task), [
            'name' => 'updated task name',
        ])
        ->assertRedirect();

    expect($task->refresh()->name)->toBe('updated task name');
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('allows clinic admin and staff to delete task for his team', function (User $user): void {
    User::factory()->create(['current_team_id' => $user->current_team_id]);

    $task = Task::factory()->create([
        'team_id' => $user->current_team_id,
    ]);

    actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertRedirect();

    expect(Task::count())->toBeInt()->toBe(0);
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('does not allow doctor to delete tasks', function (): void {
    $doctor = User::factory()->doctor()->create();
    User::factory()->create(['current_team_id' => $doctor->current_team_id]);

    $task = Task::factory()->create([
        'team_id' => $doctor->current_team_id,
    ]);

    actingAs($doctor)
        ->delete(route('tasks.destroy', $task))
        ->assertForbidden();

    expect(Task::count())->toBeInt()->toBe(1);
});

it('does not allow super admin and admin to delete task for other team', function (User $user): void {
    $team = Team::factory()->create();

    $taskUser = User::factory()->clinicAdmin()->create();
    $taskUser->update(['current_team_id' => $team->id]);

    $task = Task::factory()->create([
        'team_id' => $taskUser->current_team_id,
    ]);

    actingAs($user)
        ->delete(route('tasks.destroy', $task))
        ->assertNotFound();
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('shows users with a role of doctor and staff as assignees', function (): void {
    $doctor = User::factory()->doctor()->create();
    $staff = User::factory()->staff()->create();

    $clinicAdmin = User::factory()->clinicAdmin()->create();
    $masterAdmin = User::factory()->masterAdmin()->create();
    $patient = User::factory()->patient()->create();

    actingAs($clinicAdmin)
        ->get(route('tasks.create'))
        ->assertViewHas('assignees', fn (Collection $assignees): bool => $assignees->contains(fn (string $assignee): bool => $assignee === $doctor->name ||
                $assignee === $staff->name
        ) && $assignees->doesntContain(fn (string $assignee): bool => $assignee === $masterAdmin->name
            || $assignee === $clinicAdmin->name
            || $assignee === $patient->name
        ));
});

it('shows users with a role of patient as patients', function (): void {
    $doctor = User::factory()->doctor()->create();
    $staff = User::factory()->staff()->create();

    $clinicAdmin = User::factory()->clinicAdmin()->create();
    $masterAdmin = User::factory()->masterAdmin()->create();
    $patient = User::factory()->patient()->create();

    actingAs($clinicAdmin)
        ->get(route('tasks.create'))
        ->assertViewHas('patients', fn (Collection $patients): bool => $patients->contains(fn (string $value): bool => $value === $patient->name) &&
            $patients->doesntContain(fn (string $value): bool => $value === $doctor->name
                || $value === $staff->name
                || $value === $masterAdmin->name
                || $value === $clinicAdmin->name
            ));
});

it('shows only teams tasks for doctor, staff, and clinic admin', function (User $user): void {
    $seeTask = Task::factory()->create(['team_id' => $user->current_team_id]);
    $dontSeeTask = Task::factory()->create(['team_id' => Team::factory()->create()->id]);

    actingAs($user)
        ->get(route('tasks.index'))
        ->assertOk()
        ->assertSeeText($seeTask->name)
        ->assertDontSeeText($dontSeeTask->name);
})->with([
    fn () => User::factory()->clinicAdmin()->create(),
    fn () => User::factory()->doctor()->create(),
    fn () => User::factory()->staff()->create(),
]);

it('shows patient only his tasks', function (): void {
    $patient = User::factory()->patient()->create();

    $seeTask = Task::factory()->create([
        'team_id' => $patient->current_team_id,
        'patient_id' => $patient->id,
    ]);
    $dontSeeTask = Task::factory()->create(['team_id' => Team::factory()->create()->id]);

    actingAs($patient)
        ->get(route('tasks.index'))
        ->assertOk()
        ->assertSeeText($seeTask->name)
        ->assertDontSeeText($dontSeeTask->name);
});
