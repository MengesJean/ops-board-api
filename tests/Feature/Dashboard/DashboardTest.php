<?php

use App\Enums\ProjectHealth;
use App\Enums\ProjectStatus;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;

it('rejects a guest', function () {
    $this->getJson('/api/dashboard')->assertUnauthorized();
});

it('returns the four top-level dashboard sections', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/dashboard')
        ->assertOk()
        ->assertJsonStructure([
            'data' => [
                'stats' => [
                    'active_projects_count',
                    'completed_projects_count',
                    'warning_projects_count',
                    'critical_projects_count',
                    'overdue_tasks_count',
                    'due_today_tasks_count',
                    'upcoming_milestones_count',
                    'global_completion_rate',
                ],
                'priorities' => [
                    'overdue_tasks',
                    'due_today_tasks',
                    'upcoming_milestones',
                    'at_risk_projects',
                ],
                'projects',
                'recent_activity',
            ],
        ]);
});

it('computes the stats correctly for the authenticated customer', function () {
    $customer = Customer::factory()->create();

    // Force health to Good on the non-critical projects so the random factory
    // default doesn't accidentally bump the critical_projects_count.
    Project::factory()->forCustomer($customer)->active()->count(2)->create([
        'health' => ProjectHealth::Good,
    ]);
    Project::factory()->forCustomer($customer)->completed()->create([
        'health' => ProjectHealth::Good,
    ]);
    Project::factory()->forCustomer($customer)->critical()->active()->create();

    $project = Project::factory()->forCustomer($customer)->active()->create([
        'health' => ProjectHealth::Good,
    ]);
    Task::factory()->forProject($project)->todo()->create([
        'due_date' => now()->subDay()->toDateString(),
    ]);
    Task::factory()->forProject($project)->todo()->create([
        'due_date' => now()->subDays(3)->toDateString(),
    ]);
    Task::factory()->forProject($project)->todo()->create([
        'due_date' => now()->toDateString(),
    ]);
    ProjectMilestone::factory()->forProject($project)->pending()->create([
        'due_date' => now()->addDays(3)->toDateString(),
    ]);

    $this->actingAs($customer, 'customer')
        ->getJson('/api/dashboard')
        ->assertOk()
        ->assertJsonPath('data.stats.active_projects_count', 4)
        ->assertJsonPath('data.stats.completed_projects_count', 1)
        ->assertJsonPath('data.stats.critical_projects_count', 1)
        ->assertJsonPath('data.stats.overdue_tasks_count', 2)
        ->assertJsonPath('data.stats.due_today_tasks_count', 1)
        ->assertJsonPath('data.stats.upcoming_milestones_count', 1);
});

it('lists overdue tasks under priorities', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->active()->create();

    $overdue = Task::factory()->forProject($project)->todo()->create([
        'due_date' => now()->subDays(2)->toDateString(),
    ]);
    Task::factory()->forProject($project)->done()->create([
        'due_date' => now()->subDays(2)->toDateString(),
    ]);

    $response = $this->actingAs($customer, 'customer')
        ->getJson('/api/dashboard')
        ->assertOk();

    $ids = collect($response->json('data.priorities.overdue_tasks'))->pluck('id')->all();

    expect($ids)->toBe([$overdue->id]);
});

it('lists at-risk projects (warning or critical) but excludes completed', function () {
    $customer = Customer::factory()->create();

    $warning = Project::factory()->forCustomer($customer)->active()->create([
        'health' => ProjectHealth::Warning,
    ]);
    $critical = Project::factory()->forCustomer($customer)->active()->create([
        'health' => ProjectHealth::Critical,
    ]);
    Project::factory()->forCustomer($customer)->create([
        'status' => ProjectStatus::Completed,
        'health' => ProjectHealth::Critical,
    ]);
    Project::factory()->forCustomer($customer)->active()->create([
        'health' => ProjectHealth::Good,
    ]);

    $response = $this->actingAs($customer, 'customer')
        ->getJson('/api/dashboard')
        ->assertOk();

    $ids = collect($response->json('data.priorities.at_risk_projects'))->pluck('id')->sort()->values()->all();

    expect($ids)->toBe(collect([$warning->id, $critical->id])->sort()->values()->all());
});

it('isolates the dashboard data per customer', function () {
    $a = Customer::factory()->create();
    $b = Customer::factory()->create();

    Project::factory()->forCustomer($a)->active()->count(3)->create();
    $aTaskProject = Project::factory()->forCustomer($a)->active()->create();
    Task::factory()->forProject($aTaskProject)->todo()->create([
        'due_date' => now()->subDay()->toDateString(),
    ]);

    Project::factory()->forCustomer($b)->active()->count(7)->create();
    $bTaskProject = Project::factory()->forCustomer($b)->active()->create();
    Task::factory()->forProject($bTaskProject)->todo()->count(5)->create([
        'due_date' => now()->subDay()->toDateString(),
    ]);

    $this->actingAs($a, 'customer')
        ->getJson('/api/dashboard')
        ->assertOk()
        ->assertJsonPath('data.stats.active_projects_count', 4)
        ->assertJsonPath('data.stats.overdue_tasks_count', 1);
});

it('returns 0.0 global_completion_rate when the customer has no tasks', function () {
    $customer = Customer::factory()->create();
    Project::factory()->forCustomer($customer)->active()->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/dashboard')
        ->assertOk()
        ->assertJsonPath('data.stats.global_completion_rate', 0);
});

it('only includes the customer activity in recent_activity', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();

    Project::factory()->forCustomer($customer)->create();
    Project::factory()->forCustomer($other)->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson('/api/dashboard')
        ->assertOk();

    $projectIds = collect($response->json('data.recent_activity'))
        ->pluck('project_id')
        ->filter()
        ->unique()
        ->values()
        ->all();

    foreach ($projectIds as $projectId) {
        $belongs = Project::query()
            ->where('id', $projectId)
            ->whereHas('client', fn ($q) => $q->where('customer_id', $customer->id))
            ->exists();
        expect($belongs)->toBeTrue();
    }
});
