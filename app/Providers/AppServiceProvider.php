<?php

declare(strict_types=1);

namespace App\Providers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::define('create-task', fn (User $user): true => true);
        Gate::define('update-task', fn (User $user, Task $task): bool => $user->is_admin || $task->user_id === $user->id);
        Gate::define('delete-task', fn (User $user, Task $task): bool => $user->is_admin || $task->user_id === $user->id);
    }
}
