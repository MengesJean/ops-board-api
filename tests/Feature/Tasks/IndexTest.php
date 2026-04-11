<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $this->getJson("/api/projects/{$project->id}/tasks")->assertUnauthorized();
});

it('lists tasks for a project owned by the authenticated customer, ordered by position', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($project)->create(['title' => 'First']);
    Task::factory()->forProject($project)->create(['title' => 'Second']);
    Task::factory()->forProject($project)->create(['title' => 'Third']);

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks")
        ->assertOk();

    expect($response->json('data'))->toHaveCount(3);
    expect(collect($response->json('data'))->pluck('position')->all())->toEqual([1, 2, 3]);
    expect(collect($response->json('data'))->pluck('title')->all())
        ->toEqual(['First', 'Second', 'Third']);
});

it('does not leak tasks from other projects of the same customer', function () {
    $customer = Customer::factory()->create();
    $projectA = Project::factory()->forCustomer($customer)->create();
    $projectB = Project::factory()->forCustomer($customer)->create();

    Task::factory()->count(2)->forProject($projectA)->create();
    Task::factory()->count(5)->forProject($projectB)->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$projectA->id}/tasks")
        ->assertOk();

    expect($response->json('data'))->toHaveCount(2);
});

it('forbids listing tasks of a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();

    Task::factory()->count(3)->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks")
        ->assertForbidden();
});

it('returns 404 for a missing project', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/projects/999999/tasks')
        ->assertNotFound();
});

it('filters by status', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($project)->todo()->create();
    Task::factory()->forProject($project)->inProgress()->create();
    Task::factory()->forProject($project)->done()->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks?status=in_progress")
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.status'))->toBe('in_progress');
});

it('filters by priority', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($project)->highPriority()->create();
    Task::factory()->forProject($project)->create(['priority' => 'low']);

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks?priority=high")
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.priority'))->toBe('high');
});

it('filters by milestone', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    Task::factory()->forMilestone($milestone)->create();
    Task::factory()->forProject($project)->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks?project_milestone_id={$milestone->id}")
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.project_milestone_id'))->toBe($milestone->id);
});

it('searches by title', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($project)->create(['title' => 'Deploy v1 to production']);
    Task::factory()->forProject($project)->create(['title' => 'Audit indexes']);
    Task::factory()->forProject($project)->create(['title' => 'Deploy v2 to staging']);

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks?search=deploy")
        ->assertOk();

    expect($response->json('data'))->toHaveCount(2);
});

it('rejects an invalid status filter', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks?status=nope")
        ->assertUnprocessable()
        ->assertJsonValidationErrors('status');
});
