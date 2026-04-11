<?php

namespace App\Observers;

use App\Enums\MilestoneStatus;
use App\Models\ProjectMilestone;
use App\Services\Activity\ActivityLogger;

class ProjectMilestoneObserver
{
    public function created(ProjectMilestone $milestone): void
    {
        ActivityLogger::record($milestone, 'milestone.created', [
            'status' => $milestone->status->value,
        ]);
    }

    public function updated(ProjectMilestone $milestone): void
    {
        if (! $milestone->wasChanged('status')) {
            return;
        }

        ActivityLogger::record($milestone, 'milestone.status_changed', [
            'from' => $milestone->getOriginal('status'),
            'to' => $milestone->status->value,
        ]);

        if ($milestone->status === MilestoneStatus::Done) {
            ActivityLogger::record($milestone, 'milestone.completed', []);
        }
    }

    public function deleted(ProjectMilestone $milestone): void
    {
        ActivityLogger::record($milestone, 'milestone.deleted', [
            'title' => $milestone->title,
        ]);
    }
}
