<?php

declare(strict_types=1);

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

final class TaskController extends Controller
{
    public function index(): View
    {
        $tasks = auth()->user()->tasks;

        return view('user.tasks.index', ['tasks' => $tasks]);
    }
}
