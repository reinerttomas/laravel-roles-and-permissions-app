<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Enums\RoleEnum;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

final class TaskController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Task::class);

        $tasks = Task::with('assignee', 'patient')->get();

        return view('tasks.index', [
            'tasks' => $tasks,
        ]);
    }

    public function create(): View
    {
        Gate::authorize('create', Task::class);

        $assignees = User::whereRelation('roles', 'name', RoleEnum::Doctor->value)
            ->orWhereRelation('roles', 'name', RoleEnum::Staff->value)
            ->pluck('name', 'id');

        $patients = User::whereRelation('roles', 'name', RoleEnum::Patient->value)
            ->pluck('name', 'id');

        return view('tasks.create', [
            'assignees' => $assignees,
            'patients' => $patients,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Task::class);

        Task::create($request->only('name', 'due_date', 'assigned_to_user_id', 'patient_id'));

        return redirect()->route('tasks.index');
    }

    public function edit(Task $task): View
    {
        Gate::authorize('update', $task);

        $assignees = User::whereRelation('roles', 'name', RoleEnum::Doctor->value)
            ->orWhereRelation('roles', 'name', RoleEnum::Staff->value)
            ->pluck('name', 'id');

        $patients = User::whereRelation('roles', 'name', RoleEnum::Patient->value)
            ->pluck('name', 'id');

        return view('tasks.edit', [
            'task' => $task,
            'assignees' => $assignees,
            'patients' => $patients,
        ]);
    }

    public function update(Request $request, Task $task): RedirectResponse
    {
        Gate::authorize('update', $task);

        $task->update($request->only('name', 'due_date', 'assigned_to_user_id', 'patient_id'));

        return redirect()->route('tasks.index');
    }

    public function destroy(Task $task): RedirectResponse
    {
        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()->route('tasks.index');
    }
}
