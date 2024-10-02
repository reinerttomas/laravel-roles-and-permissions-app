<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use Illuminate\View\View;

final class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = Task::with('user')->get();

        return view('admin.tasks.index', ['tasks' => $tasks]);
    }
}
