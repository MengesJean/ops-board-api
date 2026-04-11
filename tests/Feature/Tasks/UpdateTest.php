<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->forProject($project)->create();

    $this->putJson("/api/projects/{$project->id}/tasks/{$task->id}", [])
        ->assertUnauthorized();
});

it('updates a task owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $task = Task::factory()->forProject($project)->create(['title' => 'Old']);

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", [
            'title' => 'New',
            'description' => 'Refined scope.',
            'priority' => 'high',
        ])
        ->assertOk()
        ->assertJsonPath('data.title', 'New')
        ->assertJsonPath('data.description', 'Refined scope.')
        ->assertJsonPath('data.priority', 'high');

    expect($task->fresh()->title)->toBe('New');
});

it('stamps completed_at when transitioning to done', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $task = Task::factory()
        ->forProject($project)
        ->todo()
        ->create();

    expect($task->completed_at)->toBeNull();

    $response = $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", [
            'status' => 'done',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'done');

    expect($response->json('data.completed_at'))->not->toBeNull();
    expect($task->fresh()->completed_at)->not->toBeNull();
});

it('clears completed_at when transitioning away from done', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $task = Task::factory()
        ->forProject($project)
        ->done()
        ->create();

    expect($task->fresh()->completed_at)->not->toBeNull();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", [
            'status' => 'in_progress',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'in_progress')
        ->assertJsonPath('data.completed_at', null);

    expect($task->fresh()->completed_at)->toBeNull();
});

it('attaches a milestone of the same project', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $task = Task::factory()->forProject($project)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", [
            'project_milestone_id' => $milestone->id,
        ])
        ->assertOk()
        ->assertJsonPath('data.project_milestone_id', $milestone->id);
});

it('detaches the milestone when sending null', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();
    $task = Task::factory()->forMilestone($milestone)->create();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", [
            'project_milestone_id' => null,
        ])
        ->assertOk()
        ->assertJsonPath('data.project_milestone_id', null);

    expect($task->fresh()->project_milestone_id)->toBeNull();
});

it('rejects attaching a milestone from another project', function () {
    $customer = Customer::factory()->create();
    $projectA = Project::factory()->forCustomer($customer)->create();
    $projectB = Project::factory()->forCustomer($customer)->create();
    $task = Task::factory()->forProject($projectA)->create();
    $foreign = ProjectMilestone::factory()->forProject($projectB)->create();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$projectA->id}/tasks/{$task->id}", [
            'project_milestone_id' => $foreign->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('project_milestone_id');

    expect($task->fresh()->project_milestone_id)->toBeNull();
});

it('forbids updating a task in a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();
    $task = Task::factory()->forProject($project)->create(['title' => 'Untouched']);

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", [
            'title' => 'Hijacked',
        ])
        ->assertForbidden();

    expect($task->fresh()->title)->toBe('Untouched');
});

it('rejects an invalid payload on update', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $task = Task::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/{$task->id}", [
            'title' => '',
            'status' => 'invalid',
            'priority' => 'huge',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'status', 'priority']);
});
