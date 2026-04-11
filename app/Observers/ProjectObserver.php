<?php

namespace App\Observers;

use App\Models\Project;
use App\Services\Activity\ActivityLogger;

class ProjectObserver
{
    public function created(Project $project): void
    {
        ActivityLogger::record($project, 'project.created', [
            'status' => $project->status->value,
            'health' => $project->health->value,
            'client_id' => $project->client_id,
        ]);
    }

    public function updated(Project $project): void
    {
        if ($project->wasChanged('status')) {
            ActivityLogger::record($project, 'project.status_changed', [
                'from' => $project->getOriginal('status'),
                'to' => $project->status->value,
            ]);
        }

        if ($project->wasChanged('health')) {
            ActivityLogger::record($project, 'project.health_changed', [
                'from' => $project->getOriginal('health'),
                'to' => $project->health->value,
            ]);
        }
    }

    public function deleted(Project $project): void
    {
        ActivityLogger::record($project, 'project.deleted', [
            'name' => $project->name,
        ]);
    }
}
