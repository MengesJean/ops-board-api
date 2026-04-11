<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\Task;

it('rejects a guest', function () {
    $project = Project::factory()->create();

    $this->getJson("/api/projects/{$project->id}/activity")->assertUnauthorized();
});

it('returns the activity timeline for a project owned by the customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($project)->todo()->create();
    Task::factory()->forProject($project)->todo()->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/activity")
        ->assertOk();

    $events = collect($response->json('data'))->pluck('event')->all();

    expect($events)->toContain('project.created');
    expect($events)->toContain('task.created');
});

it('orders activity by descending created_at', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($project)->create();
    Task::factory()->forProject($project)->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/activity")
        ->assertOk();

    $timestamps = collect($response->json('data'))->pluck('created_at')->all();
    $sorted = $timestamps;
    rsort($sorted);

    expect($timestamps)->toBe($sorted);
});

it('forbids reading the activity of a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/activity")
        ->assertForbidden();
});

it('does not leak activity from a sibling project', function () {
    $customer = Customer::factory()->create();
    $a = Project::factory()->forCustomer($customer)->create();
    $b = Project::factory()->forCustomer($customer)->create();

    Task::factory()->forProject($a)->create();
    Task::factory()->forProject($b)->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$a->id}/activity")
        ->assertOk();

    $projectIds = collect($response->json('data'))->pluck('project_id')->unique()->all();

    expect($projectIds)->toBe([$a->id]);
});
