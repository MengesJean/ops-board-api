<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $this->postJson("/api/projects/{$project->id}/tasks", [])->assertUnauthorized();
});

it('creates a task on a project owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $payload = [
        'title' => 'Write the technical brief',
        'description' => 'Cover the API contract and the rollout plan.',
        'status' => 'todo',
        'priority' => 'high',
        'due_date' => '2026-06-15',
    ];

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/tasks", $payload)
        ->assertCreated()
        ->assertJsonPath('data.title', 'Write the technical brief')
        ->assertJsonPath('data.project_id', $project->id)
        ->assertJsonPath('data.project_milestone_id', null)
        ->assertJsonPath('data.status', 'todo')
        ->assertJsonPath('data.priority', 'high')
        ->assertJsonPath('data.position', 1)
        ->assertJsonPath('data.completed_at', null);

    $this->assertDatabaseHas('task', [
        'project_id' => $project->id,
        'title' => 'Write the technical brief',
        'status' => 'todo',
        'priority' => 'high',
        'position' => 1,
    ]);
});

it('auto-assigns position as max + 1 within the project', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($project)->create();
    Task::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/tasks", [
            'title' => 'Third',
            'status' => 'todo',
            'priority' => 'medium',
        ])
        ->assertCreated()
        ->assertJsonPath('data.position', 3);
});

it('stamps completed_at when creating a task directly with status done', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $response = $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/tasks", [
            'title' => 'Already done',
            'status' => 'done',
            'priority' => 'low',
        ])
        ->assertCreated()
        ->assertJsonPath('data.status', 'done');

    expect($response->json('data.completed_at'))->not->toBeNull();
});

it('accepts a milestone of the same project', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/tasks", [
            'title' => 'Linked task',
            'status' => 'todo',
            'priority' => 'medium',
            'project_milestone_id' => $milestone->id,
        ])
        ->assertCreated()
        ->assertJsonPath('data.project_milestone_id', $milestone->id);
});

it('rejects a milestone from another project of the same customer', function () {
    $customer = Customer::factory()->create();
    $projectA = Project::factory()->forCustomer($customer)->create();
    $projectB = Project::factory()->forCustomer($customer)->create();
    $foreignMilestone = ProjectMilestone::factory()->forProject($projectB)->create();

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$projectA->id}/tasks", [
            'title' => 'Cross-project hijack',
            'status' => 'todo',
            'priority' => 'medium',
            'project_milestone_id' => $foreignMilestone->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('project_milestone_id');

    expect(Task::where('project_id', $projectA->id)->count())->toBe(0);
});

it('rejects a milestone from another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $myProject = Project::factory()->forCustomer($customer)->create();
    $foreignProject = Project::factory()->forCustomer($other)->create();
    $foreignMilestone = ProjectMilestone::factory()->forProject($foreignProject)->create();

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$myProject->id}/tasks", [
            'title' => 'Cross-customer hijack',
            'status' => 'todo',
            'priority' => 'medium',
            'project_milestone_id' => $foreignMilestone->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('project_milestone_id');
});

it('forbids creating a task on a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/tasks", [
            'title' => 'Hijack',
            'status' => 'todo',
            'priority' => 'medium',
        ])
        ->assertForbidden();

    expect(Task::where('project_id', $project->id)->count())->toBe(0);
});

it('rejects an invalid payload', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/tasks", [
            'title' => '',
            'status' => 'nope',
            'priority' => 'huge',
            'due_date' => 'not-a-date',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'status', 'priority', 'due_date']);
});
