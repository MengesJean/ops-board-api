<?php

namespace Database\Factories;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Task>
 */
class TaskFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'project_id' => Project::factory(),
            'project_milestone_id' => null,
            'title' => fake()->randomElement([
                'Write the technical brief',
                'Prepare the homepage mockup',
                'Fix the login bug',
                'Validate the hero copy',
                'Deploy v1 to production',
                'Audit the database indexes',
            ]),
            'description' => fake()->optional()->paragraph(),
            'status' => TaskStatus::Todo,
            'priority' => fake()->randomElement(TaskPriority::cases()),
            'due_date' => fake()->optional()->dateTimeBetween('now', '+3 months'),
        ];
    }

    public function todo(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TaskStatus::Todo,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TaskStatus::InProgress,
        ]);
    }

    public function done(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => TaskStatus::Done,
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes): array => [
            'priority' => TaskPriority::High,
        ]);
    }

    public function forProject(Project $project): static
    {
        return $this->state(fn (array $attributes): array => [
            'project_id' => $project->id,
        ]);
    }

    public function forMilestone(ProjectMilestone $milestone): static
    {
        return $this->state(fn (array $attributes): array => [
            'project_id' => $milestone->project_id,
            'project_milestone_id' => $milestone->id,
        ]);
    }

    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes): array => [
            'project_id' => Project::factory()->forCustomer($customer),
        ]);
    }
}
