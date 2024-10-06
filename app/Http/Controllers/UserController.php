<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

final class UserController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', User::class);

        $users = User::with('roles')
            ->whereHas('roles', function (Builder $query) {
                return $query->whereIn('name', [
                    RoleEnum::ClinicAdmin->value,
                    RoleEnum::Doctor->value,
                    RoleEnum::Staff->value,
                ]);
            })
            ->whereRelation('teams', 'team_id', auth()->user()->current_team_id)
            ->get();

        return view('user.index', [
            'users' => $users,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', User::class);

        $roles = Role::whereIn('name', [RoleEnum::ClinicAdmin->value, RoleEnum::Doctor->value, RoleEnum::Staff->value])
            ->pluck('name', 'id');

        return view('user.create', [
            'roles' => $roles,
        ]);
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $user = User::create($request->except('role_id'));

        $user->assignRole($request->integer('role_id'));

        return redirect()->route('users.index');
    }
}
