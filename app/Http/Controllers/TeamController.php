<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\StoreTeamRequest;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use Symfony\Component\HttpFoundation\Response;

final class TeamController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Team::class);

        $teams = Team::whereNot('name', 'Master Admin Team')->paginate();

        return view('teams.index', [
            'teams' => $teams,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Team::class);

        $users = User::whereRelation('rolesWithoutTeam', 'name', RoleEnum::ClinicOwner)
            ->pluck('name', 'id');

        return view('teams.create', [
            'users' => $users,
        ]);
    }

    public function store(StoreTeamRequest $request): RedirectResponse
    {
        Gate::authorize('create', Team::class);

        $team = Team::create(['name' => $request->input('clinic_name')]);

        if ($request->integer('user_id') > 0) {
            $user = User::find($request->integer('user_id'));
            $user->update(['current_team_id' => $team->id]);
        } else {
            $user = User::create($request->only(['name', 'email', 'password'])
                + ['current_team_id' => $team->id]);
        }

        $user->teams()->attach($team->id, [
            'model_type' => User::class,
            'role_id' => Role::where('name', RoleEnum::ClinicOwner->value)->first()->id,
        ]);

        return redirect()->route('teams.index');
    }

    public function changeCurrentTeam(int $teamId)
    {
        Gate::authorize('changeTeam', Team::class);

        $team = auth()->user()->teams()->findOrFail($teamId);

        if (! auth()->user()->belongsToTeam($team)) {
            abort(Response::HTTP_FORBIDDEN);
        }

        // Change team
        auth()->user()->update(['current_team_id' => $team->id]);
        setPermissionsTeamId($team->id);
        auth()->user()->unsetRelation('roles')->unsetRelation('permissions');

        return redirect(route('dashboard'), Response::HTTP_SEE_OTHER);
    }
}
