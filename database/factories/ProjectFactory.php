<?php

namespace Database\Factories;

use App\Enums\ProjectHealth;
use App\Enums\ProjectPriority;
use App\Enums\ProjectStatus;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Project>
 */
class ProjectFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $start = fake()->optional()->dateTimeBetween('-3 months', '+1 month');
        $due = $start
            ? fake()->dateTimeBetween($start, '+6 months')
            : fake()->optional()->dateTimeBetween('now', '+6 months');

        return [
            'client_id' => Client::factory(),
            'name' => fake()->catchPhrase(),
            'reference' => fake()->optional()->bothify('PRJ-####'),
            'description' => fake()->optional()->paragraph(),
            'status' => fake()->randomElement(ProjectStatus::cases()),
            'priority' => fake()->randomElement(ProjectPriority::cases()),
            'health' => fake()->randomElement(ProjectHealth::cases()),
            'start_date' => $start,
            'due_date' => $due,
            'budget' => fake()->optional()->randomFloat(2, 500, 100000),
            'notes' => fake()->optional()->sentence(),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProjectStatus::Active,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProjectStatus::Completed,
        ]);
    }

    public function onHold(): static
    {
        return $this->state(fn (array $attributes): array => [
            'status' => ProjectStatus::OnHold,
        ]);
    }

    public function highPriority(): static
    {
        return $this->state(fn (array $attributes): array => [
            'priority' => ProjectPriority::High,
        ]);
    }

    public function critical(): static
    {
        return $this->state(fn (array $attributes): array => [
            'health' => ProjectHealth::Critical,
        ]);
    }

    public function forClient(Client $client): static
    {
        return $this->state(fn (array $attributes): array => [
            'client_id' => $client->id,
        ]);
    }

    public function forCustomer(Customer $customer): static
    {
        return $this->state(fn (array $attributes): array => [
            'client_id' => Client::factory()->forCustomer($customer),
        ]);
    }
}
