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
            'created_at' => $this->created_at?->toIso8601String(),
            'updated_at' => $this->updated_at?->toIso8601String(),
        ];
    }
}
