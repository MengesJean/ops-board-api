<?php

namespace App\Models;

use App\Enums\MilestoneStatus;
use App\Observers\ProjectMilestoneObserver;
use Database\Factories\ProjectMilestoneFactory;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[ObservedBy([ProjectMilestoneObserver::class])]
class ProjectMilestone extends Model
{
    /** @use HasFactory<ProjectMilestoneFactory> */
    use HasFactory;

    protected $table = 'project_milestone';

    /**
     * @var list<string>
     */
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'status',
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
     * @return HasMany<Task, $this>
     */
    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('position')->orderBy('id');
    }

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => MilestoneStatus::class,
            'due_date' => 'date',
            'completed_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        // Auto-assign position on creation: max(position) + 1 for the project,
        // unless an explicit position was already set (e.g. via seeders).
        static::creating(function (self $milestone): void {
            if ($milestone->position === null) {
                $milestone->position = (int) static::query()
                    ->where('project_id', $milestone->project_id)
                    ->max('position') + 1;
            }
        });

        // Keep `completed_at` consistent with the `done` status:
        //  - entering `done` stamps the column when it is null;
        //  - leaving `done` clears the column.
        // Centralised here so the API, Filament, and seeders all behave the same.
        static::saving(function (self $milestone): void {
            if (! $milestone->isDirty('status')) {
                return;
            }

            if ($milestone->status === MilestoneStatus::Done) {
                if ($milestone->completed_at === null) {
                    $milestone->completed_at = now();
                }

                return;
            }

            $milestone->completed_at = null;
        });
    }
}
