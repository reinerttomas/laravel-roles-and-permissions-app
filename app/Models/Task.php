<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\RoleEnum;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

final class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'due_date',
        'assigned_to_user_id',
        'patient_id',
        'team_id',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    protected static function booted(): void
    {
        self::creating(function (Task $task): void {
            if (auth()->check()) {
                $task->team_id = auth()->user()->current_team_id;
            }
        });

        self::addGlobalScope('team-tasks', function (Builder $query): void {
            if (auth()->check()) {
                $query->where('team_id', auth()->user()->current_team_id);

                if (auth()->user()->hasRole(RoleEnum::Patient)) {
                    $query->where('patient_id', auth()->id());
                }
            }
        });
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
}
