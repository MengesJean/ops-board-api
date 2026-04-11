<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;

it('rejects a guest', function () {
    $project = Project::factory()->create();

    $this->getJson("/api/projects/{$project->id}/progress")->assertUnauthorized();
});

it('returns the progression of a project owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($project)->todo()->count(2)->create();
    Task::factory()->forProject($project)->inProgress()->count(1)->create();
    Task::factory()->forProject($project)->done()->count(2)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/progress")
        ->assertOk()
        ->assertJsonPath('data.project.total_tasks', 5)
        ->assertJsonPath('data.project.todo_tasks', 2)
        ->assertJsonPath('data.project.in_progress_tasks', 1)
        ->assertJsonPath('data.project.completed_tasks', 2)
        ->assertJsonPath('data.project.completion_rate', 0.4)
        ->assertJsonPath('data.project.has_tasks', true);
});

it('forbids accessing the progression of a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/progress")
        ->assertForbidden();
});

it('counts overdue tasks (not done, due_date in the past)', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($project)->todo()->create([
        'due_date' => now()->subDay()->toDateString(),
    ]);
    Task::factory()->forProject($project)->done()->create([
        'due_date' => now()->subDay()->toDateString(),
    ]);
    Task::factory()->forProject($project)->todo()->create([
        'due_date' => now()->addDay()->toDateString(),
    ]);

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/progress")
        ->assertOk()
        ->assertJsonPath('data.project.overdue_tasks', 1);
});

it('handles a project without any tasks', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/progress")
        ->assertOk()
        ->assertJsonPath('data.project.total_tasks', 0)
        ->assertJsonPath('data.project.completion_rate', 0)
        ->assertJsonPath('data.project.has_tasks', false)
        ->assertJsonPath('data.project.next_due_task', null);
});

it('returns null completion_rate for milestones without tasks', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $emptyMilestone = ProjectMilestone::factory()->forProject($project)->create();
    $busyMilestone = ProjectMilestone::factory()->forProject($project)->create();

    Task::factory()->forProject($project)->forMilestone($busyMilestone)->done()->create();
    Task::factory()->forProject($project)->forMilestone($busyMilestone)->todo()->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/progress")
        ->assertOk();

    $milestones = collect($response->json('data.milestones'));

    expect($milestones->firstWhere('id', $emptyMilestone->id)['completion_rate'])->toBeNull();
    expect($milestones->firstWhere('id', $busyMilestone->id)['completion_rate'])->toBe(0.5);
});

it('returns the next-due task and milestone', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $closer = Task::factory()->forProject($project)->todo()->create([
        'due_date' => now()->addDay()->toDateString(),
    ]);
    Task::factory()->forProject($project)->todo()->create([
        'due_date' => now()->addWeek()->toDateString(),
    ]);

    $closerMilestone = ProjectMilestone::factory()->forProject($project)->pending()->create([
        'due_date' => now()->addDays(3)->toDateString(),
    ]);
    ProjectMilestone::factory()->forProject($project)->pending()->create([
        'due_date' => now()->addMonth()->toDateString(),
    ]);

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/progress")
        ->assertOk()
        ->assertJsonPath('data.project.next_due_task.id', $closer->id)
        ->assertJsonPath('data.project.next_due_milestone.id', $closerMilestone->id);
});

it('exposes tasks_count and progress on the project show endpoint', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    Task::factory()->forProject($project)->done()->count(2)->create();
    Task::factory()->forProject($project)->todo()->count(1)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}")
        ->assertOk()
        ->assertJsonPath('data.tasks_count', 3)
        ->assertJsonPath('data.completed_tasks_count', 2)
        ->assertJsonPath('data.progress.total_tasks', 3)
        ->assertJsonPath('data.progress.completed_tasks', 2)
        ->assertJsonPath('data.progress.completion_rate', 0.6667);
});
