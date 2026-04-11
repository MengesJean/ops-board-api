<?php

namespace App\Http\Resources\Activity;

use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin ActivityLog
 */
class ActivityLogResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'event' => $this->event,
            'subject' => [
                'type' => $this->shortSubjectType(),
                'id' => $this->subject_id,
                'label' => $this->resolveSubjectLabel(),
            ],
            'project_id' => $this->project_id,
            'actor' => $this->resolveActor(),
            'properties' => $this->properties,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }

    private function shortSubjectType(): string
    {
        return match ($this->subject_type) {
            Project::class => 'project',
            Task::class => 'task',
            ProjectMilestone::class => 'milestone',
            Client::class => 'client',
            default => (string) $this->subject_type,
        };
    }

    private function resolveSubjectLabel(): ?string
    {
        // The subject morph may already be loaded by the controller (eager
        // load on `subject`). Fall back to the snapshot stashed in
        // `properties.label` so the timeline still reads correctly after a
        // subject row has been deleted.
        $subject = $this->resource->subject ?? null;

        $live = match (true) {
            $subject instanceof Project, $subject instanceof Client => $subject->name,
            $subject instanceof Task, $subject instanceof ProjectMilestone => $subject->title,
            default => null,
        };

        return $live ?? ($this->properties['label'] ?? null);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function resolveActor(): ?array
    {
        if ($this->actor_id === null) {
            return null;
        }

        $actor = $this->resource->actor ?? null;

        return [
            'type' => match ($this->actor_type) {
                Customer::class => 'customer',
                User::class => 'user',
                default => (string) $this->actor_type,
            },
            'id' => $this->actor_id,
            'name' => $actor?->name,
        ];
    }
}
