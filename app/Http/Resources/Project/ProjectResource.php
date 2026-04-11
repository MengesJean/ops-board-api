<?php

namespace App\Http\Resources\Project;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Project
 */
class ProjectResource extends JsonResource
{
    /**
     * Optional progression snapshot, attached by the controller via
     * {@see self::withProgress()} when the endpoint wants to enrich the
     * project payload without forcing the consumer to make a second call.
     *
     * @var array<string, mixed>|null
     */
    public ?array $progress = null;

    /**
     * @param  array<string, mixed>  $progress
     */
    public function withProgress(array $progress): static
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'name' => $this->name,
            'reference' => $this->reference,
            'description' => $this->description,
            'status' => $this->status->value,
            'priority' => $this->priority->value,
            'health' => $this->health->value,
            'start_date' => $this->start_date?->toDateString(),
            'due_date' => $this->due_date?->toDateString(),
            'budget' => $this->budget,
            'notes' => $this->notes,
            'client' => $this->whenLoaded('client', fn (): array => [
                'id' => $this->client->id,
                'name' => $this->client->name,
                'company_name' => $this->client->company_name,
            ]),
            'tasks_count' => $this->whenCounted('tasks'),
            'completed_tasks_count' => $this->whenCounted('completed_tasks'),
            'progress' => $this->when($this->progress !== null, fn () => $this->progress),
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
