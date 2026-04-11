<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $this->getJson("/api/projects/{$project->id}/milestones/{$milestone->id}")
        ->assertUnauthorized();
});

it('returns a milestone owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create(['title' => 'Mine']);

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/milestones/{$milestone->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $milestone->id)
        ->assertJsonPath('data.title', 'Mine')
        ->assertJsonPath('data.project_id', $project->id);
});

it('forbids accessing a milestone in a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/milestones/{$milestone->id}")
        ->assertForbidden();
});

it('returns 404 when the milestone exists but belongs to a different project (scoped binding)', function () {
    $customer = Customer::factory()->create();
    $projectA = Project::factory()->forCustomer($customer)->create();
    $projectB = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($projectB)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$projectA->id}/milestones/{$milestone->id}")
        ->assertNotFound();
});

it('returns 404 for a missing milestone', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}/milestones/999999")
        ->assertNotFound();
});
