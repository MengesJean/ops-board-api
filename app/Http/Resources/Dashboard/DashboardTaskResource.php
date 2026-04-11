<?php

namespace App\Http\Resources\Dashboard;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Compact representation of a Task for the dashboard's "priorities" section.
 * Stays small on purpose: just enough for the front to link the user back to
 * the full task on its project board.
 *
 * @mixin Task
 */
class DashboardTaskResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'due_date' => $this->due_date?->toDateString(),
            'project' => $this->whenLoaded('project', fn (): array => [
                'id' => $this->project->id,
                'name' => $this->project->name,
            ]),
        ];
    }
}
