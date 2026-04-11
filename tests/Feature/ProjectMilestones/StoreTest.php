<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $this->postJson("/api/projects/{$project->id}/milestones", [])->assertUnauthorized();
});

it('creates a milestone on a project owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $payload = [
        'title' => 'Discovery',
        'description' => 'Stakeholder interviews and scoping.',
        'status' => 'pending',
        'due_date' => '2026-06-15',
    ];

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/milestones", $payload)
        ->assertCreated()
        ->assertJsonPath('data.title', 'Discovery')
        ->assertJsonPath('data.project_id', $project->id)
        ->assertJsonPath('data.status', 'pending')
        ->assertJsonPath('data.position', 1)
        ->assertJsonPath('data.completed_at', null);

    $this->assertDatabaseHas('project_milestone', [
        'project_id' => $project->id,
        'title' => 'Discovery',
        'status' => 'pending',
        'position' => 1,
    ]);
});

it('auto-assigns position as max + 1 within the project', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    ProjectMilestone::factory()->forProject($project)->create();
    ProjectMilestone::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/milestones", [
            'title' => 'Third',
            'status' => 'pending',
        ])
        ->assertCreated()
        ->assertJsonPath('data.position', 3);
});

it('stamps completed_at when creating a milestone directly with status done', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $response = $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/milestones", [
            'title' => 'Already done',
            'status' => 'done',
        ])
        ->assertCreated()
        ->assertJsonPath('data.status', 'done');

    expect($response->json('data.completed_at'))->not->toBeNull();
});

it('forbids creating a milestone on a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/milestones", [
            'title' => 'Hijack',
            'status' => 'pending',
        ])
        ->assertForbidden();

    expect(ProjectMilestone::where('project_id', $project->id)->count())->toBe(0);
});

it('rejects an invalid payload', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->postJson("/api/projects/{$project->id}/milestones", [
            'title' => '',
            'status' => 'nope',
            'due_date' => 'not-a-date',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'status', 'due_date']);
});
