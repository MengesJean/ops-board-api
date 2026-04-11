<?php

namespace App\Observers;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Services\Activity\ActivityLogger;

class TaskObserver
{
    public function created(Task $task): void
    {
        ActivityLogger::record($task, 'task.created', [
            'status' => $task->status->value,
            'priority' => $task->priority->value,
            'project_milestone_id' => $task->project_milestone_id,
        ]);
    }

    public function updated(Task $task): void
    {
        if ($task->wasChanged('status')) {
            ActivityLogger::record($task, 'task.status_changed', [
                'from' => $task->getOriginal('status'),
                'to' => $task->status->value,
            ]);

            if ($task->status === TaskStatus::Done) {
                ActivityLogger::record($task, 'task.completed', []);
            } elseif ($task->status === TaskStatus::InProgress) {
                ActivityLogger::record($task, 'task.started', []);
            }
        }

        if ($task->wasChanged('project_milestone_id')) {
            $from = $task->getOriginal('project_milestone_id');
            $to = $task->project_milestone_id;

            $event = match (true) {
                $from === null && $to !== null => 'task.milestone_attached',
                $from !== null && $to === null => 'task.milestone_detached',
                default => 'task.milestone_changed',
            };

            ActivityLogger::record($task, $event, [
                'from' => $from,
                'to' => $to,
            ]);
        }
    }

    public function deleted(Task $task): void
    {
        ActivityLogger::record($task, 'task.deleted', [
            'title' => $task->title,
        ]);
    }
}
