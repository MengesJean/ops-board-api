<?php

namespace App\Http\Resources\Dashboard;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * Compact project shape for the dashboard. Bundles a derived `progress`
 * block computed from the `withCount` aliases the controller selected.
 *
 * @mixin Project
 */
class DashboardProjectResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $total = (int) ($this->tasks_count ?? 0);
        $done = (int) ($this->completed_tasks_count ?? 0);
        $today = Carbon::today();

        $isOverdue = $this->due_date !== null
            && $this->due_date->lt($today)
            && ! in_array($this->status, [ProjectStatus::Completed, ProjectStatus::Cancelled], true);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'status' => $this->status->value,
            'health' => $this->health->value,
            'priority' => $this->priority->value,
            'due_date' => $this->due_date?->toDateString(),
            'is_overdue' => $isOverdue,
            'client' => $this->whenLoaded('client', fn (): array => [
                'id' => $this->client->id,
                'name' => $this->client->name,
            ]),
            'progress' => [
                'total_tasks' => $total,
                'completed_tasks' => $done,
                'completion_rate' => $total > 0 ? round($done / $total, 4) : 0.0,
            ],
        ];
    }
}
