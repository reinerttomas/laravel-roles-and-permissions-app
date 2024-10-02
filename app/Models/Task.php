<?php

declare(strict_types=1);

namespace App\Models;

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
        'user_id',
        'name',
        'due_date',
    ];

    protected static function booted(): void
    {
        self::addGlobalScope(function (Builder $query): void {
            if (auth()->check() && ! auth()->user()->is_admin) {
                $query->where('user_id', auth()->id());
            }
        });
    }

    /**
     * @return array<string, mixed>
     */
    protected function casts(): array
    {
        return [
            'due_date' => 'date',
        ];
    }

    /**
     * @return BelongsTo<User, Task>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
