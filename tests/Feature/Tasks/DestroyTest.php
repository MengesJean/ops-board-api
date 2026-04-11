<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\Task;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->forProject($project)->create();

    $this->deleteJson("/api/projects/{$project->id}/tasks/{$task->id}")
        ->assertUnauthorized();
});

it('deletes a task owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $task = Task::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->deleteJson("/api/projects/{$project->id}/tasks/{$task->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('task', ['id' => $task->id]);
});

it('forbids deleting a task owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();
    $task = Task::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->deleteJson("/api/projects/{$project->id}/tasks/{$task->id}")
        ->assertForbidden();

    $this->assertDatabaseHas('task', ['id' => $task->id]);
});

it('cascades deletion when the parent project is removed', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    Task::factory()->count(3)->forProject($project)->create();

    $project->delete();

    expect(Task::where('project_id', $project->id)->count())->toBe(0);
});

it('keeps the task and nullifies the milestone fk when the milestone is deleted', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = \App\Models\ProjectMilestone::factory()->forProject($project)->create();
    $task = Task::factory()->forMilestone($milestone)->create();

    $milestone->delete();

    $fresh = $task->fresh();
    expect($fresh)->not->toBeNull();
    expect($fresh->project_milestone_id)->toBeNull();
});
