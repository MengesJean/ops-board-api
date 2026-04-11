<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\Task;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $task = Task::factory()->forProject($project)->create();

    $this->getJson("/api/projects/{$project->id}/tasks/{$task->id}")
        ->assertUnauthorized();
});

it('returns a task owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $task = Task::factory()->forProject($project)->create(['title' => 'Mine']);

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks/{$task->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $task->id)
        ->assertJsonPath('data.title', 'Mine')
        ->assertJsonPath('data.project_id', $project->id);
});

it('forbids accessing a task in a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();
    $task = Task::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks/{$task->id}")
        ->assertForbidden();
});

it('returns 404 when the task exists but belongs to a different project (scoped binding)', function () {
    $customer = Customer::factory()->create();
    $projectA = Project::factory()->forCustomer($customer)->create();
    $projectB = Project::factory()->forCustomer($customer)->create();
    $task = Task::factory()->forProject($projectB)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$projectA->id}/tasks/{$task->id}")
        ->assertNotFound();
});

it('returns 404 for a missing task', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/tasks/999999")
        ->assertNotFound();
});
