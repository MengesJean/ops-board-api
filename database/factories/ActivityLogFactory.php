<?php

namespace Database\Factories;

use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ActivityLog>
 */
class ActivityLogFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customer::factory(),
            'project_id' => null,
            'actor_type' => null,
            'actor_id' => null,
            'subject_type' => Project::class,
            'subject_id' => 1,
            'event' => 'project.created',
            'properties' => [],
            'created_at' => now(),
        ];
    }

    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes): array => [
            'customer_id' => $customer->id,
        ]);
    }

    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes): array => [
            'customer_id' => $project->client?->customer_id ?? $project->loadMissing('client')->client?->customer_id,
            'project_id' => $project->id,
            'subject_type' => Project::class,
            'subject_id' => $project->id,
        ]);
    }
}
