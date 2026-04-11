<?php

namespace Database\Factories;

use App\Enums\MilestoneStatus;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProjectMilestone>
 */
class ProjectMilestoneFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'title' => fake()->randomElement([
                'Discovery',
                'Scope sign-off',
                'Design ready',
                'Development complete',
                'Client UAT',
                'Production launch',
            ]),
            'description' => fake()->optional()->paragraph(),
            'status' => MilestoneStatus::Pending,
            'due_date' => fake()->optional()->dateTimeBetween('now', '+6 months'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => MilestoneStatus::Pending,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => MilestoneStatus::InProgress,
        ]);
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => MilestoneStatus::Done,
        ]);
    }

    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes): array => [
            'project_id' => $project->id,
        ]);
    }

    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes): array => [
            'project_id' => Project::factory()->forCustomer($customer),
        ]);
    }
}
