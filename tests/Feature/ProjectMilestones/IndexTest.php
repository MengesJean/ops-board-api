<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $this->getJson("/api/projects/{$project->id}/milestones")->assertUnauthorized();
});

it('lists milestones for a project owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    ProjectMilestone::factory()->forProject($project)->create(['title' => 'Discovery']);
    ProjectMilestone::factory()->forProject($project)->create(['title' => 'Design']);
    ProjectMilestone::factory()->forProject($project)->create(['title' => 'Launch']);

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/milestones")
        ->assertOk();

    expect($response->json('data'))->toHaveCount(3);
    expect(collect($response->json('data'))->pluck('position')->all())->toEqual([1, 2, 3]);
    expect(collect($response->json('data'))->pluck('title')->all())
        ->toEqual(['Discovery', 'Design', 'Launch']);
});

it('does not leak milestones from other projects of the same customer', function () {
    $customer = Customer::factory()->create();
    $projectA = Project::factory()->forCustomer($customer)->create();
    $projectB = Project::factory()->forCustomer($customer)->create();

    ProjectMilestone::factory()->count(2)->forProject($projectA)->create();
    ProjectMilestone::factory()->count(5)->forProject($projectB)->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$projectA->id}/milestones")
        ->assertOk();

    expect($response->json('data'))->toHaveCount(2);
});

it('forbids listing milestones of a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();

    ProjectMilestone::factory()->count(3)->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/milestones")
        ->assertForbidden();
});

it('returns 404 for a missing project', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/projects/999999/milestones')
        ->assertNotFound();
});
