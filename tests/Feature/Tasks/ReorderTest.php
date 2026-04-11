<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\Task;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $this->patchJson("/api/projects/{$project->id}/tasks/reorder", [])
        ->assertUnauthorized();
});

it('reorders tasks according to the provided list', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $a = Task::factory()->forProject($project)->create(['title' => 'A']);
    $b = Task::factory()->forProject($project)->create(['title' => 'B']);
    $c = Task::factory()->forProject($project)->create(['title' => 'C']);

    $response = $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/reorder", [
            'task_ids' => [$c->id, $a->id, $b->id],
        ])
        ->assertOk();

    expect(collect($response->json('data'))->pluck('id')->all())
        ->toEqual([$c->id, $a->id, $b->id]);
    expect(collect($response->json('data'))->pluck('position')->all())
        ->toEqual([1, 2, 3]);

    expect($a->fresh()->position)->toBe(2);
    expect($b->fresh()->position)->toBe(3);
    expect($c->fresh()->position)->toBe(1);
});

it('rejects a reorder list missing entries', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $a = Task::factory()->forProject($project)->create();
    Task::factory()->forProject($project)->create();
    Task::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/reorder", [
            'task_ids' => [$a->id],
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('task_ids');
});

it('rejects a reorder list including IDs from another project', function () {
    $customer = Customer::factory()->create();
    $projectA = Project::factory()->forCustomer($customer)->create();
    $projectB = Project::factory()->forCustomer($customer)->create();

    $a1 = Task::factory()->forProject($projectA)->create();
    $a2 = Task::factory()->forProject($projectA)->create();
    $foreign = Task::factory()->forProject($projectB)->create();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$projectA->id}/tasks/reorder", [
            'task_ids' => [$a1->id, $a2->id, $foreign->id],
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('task_ids.2');
});

it('rejects duplicate IDs', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $a = Task::factory()->forProject($project)->create();
    Task::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/reorder", [
            'task_ids' => [$a->id, $a->id],
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('task_ids.1');
});

it('forbids reordering tasks of a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();

    $tasks = Task::factory()->count(2)->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/tasks/reorder", [
            'task_ids' => $tasks->pluck('id')->all(),
        ])
        ->assertForbidden();
});
