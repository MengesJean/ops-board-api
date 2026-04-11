<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $this->putJson("/api/projects/{$project->id}/milestones/{$milestone->id}", [])
        ->assertUnauthorized();
});

it('updates a milestone owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create(['title' => 'Old']);

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/milestones/{$milestone->id}", [
            'title' => 'New',
            'description' => 'Refined scope.',
        ])
        ->assertOk()
        ->assertJsonPath('data.title', 'New')
        ->assertJsonPath('data.description', 'Refined scope.');

    expect($milestone->fresh()->title)->toBe('New');
});

it('stamps completed_at when transitioning to done', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()
        ->forProject($project)
        ->pending()
        ->create();

    expect($milestone->completed_at)->toBeNull();

    $response = $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/milestones/{$milestone->id}", [
            'status' => 'done',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'done');

    expect($response->json('data.completed_at'))->not->toBeNull();
    expect($milestone->fresh()->completed_at)->not->toBeNull();
});

it('clears completed_at when transitioning away from done', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()
        ->forProject($project)
        ->done()
        ->create();

    expect($milestone->fresh()->completed_at)->not->toBeNull();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/milestones/{$milestone->id}", [
            'status' => 'in_progress',
        ])
        ->assertOk()
        ->assertJsonPath('data.status', 'in_progress')
        ->assertJsonPath('data.completed_at', null);

    expect($milestone->fresh()->completed_at)->toBeNull();
});

it('forbids updating a milestone in a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create(['title' => 'Untouched']);

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/milestones/{$milestone->id}", [
            'title' => 'Hijacked',
        ])
        ->assertForbidden();

    expect($milestone->fresh()->title)->toBe('Untouched');
});

it('rejects an invalid payload on update', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->patchJson("/api/projects/{$project->id}/milestones/{$milestone->id}", [
            'title' => '',
            'status' => 'invalid',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['title', 'status']);
});
