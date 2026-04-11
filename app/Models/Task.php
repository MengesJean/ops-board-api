<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Database\Factories\TaskFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    /** @use HasFactory<TaskFactory> */
    use HasFactory;

    protected $table = 'task';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'project_milestone_id',
        'title',
        'description',
        'status',
        'priority',
        'due_date',
    ];

    /**
     * @return BelongsTo<Project, $this>
     */
    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * @return BelongsTo<ProjectMilestone, $this>
     */
    public function milestone(): BelongsTo
    {
        return $this->belongsTo(ProjectMilestone::class, 'project_milestone_id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => TaskStatus::class,
            'priority' => TaskPriority::class,
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Auto-assign position on creation: max(position) + 1 for the project,
        // unless an explicit position was already set (e.g. via seeders).
        static::creating(function (self $task): void {
            if ($task->position === null) {
                $task->position = (int) static::query()
                    ->where('project_id', $task->project_id)
                    ->max('position') + 1;
            }
        });

        // Keep `completed_at` consistent with the `done` status:
        //  - entering `done` stamps the column when it is null;
        //  - leaving `done` clears the column.
        // Centralised here so the API, Filament, and seeders all behave the same.
        static::saving(function (self $task): void {
            if (! $task->isDirty('status')) {
                return;
            }

            if ($task->status === TaskStatus::Done) {
                if ($task->completed_at === null) {
                    $task->completed_at = now();
                }

                return;
            }

            $task->completed_at = null;
        });
    }
}
