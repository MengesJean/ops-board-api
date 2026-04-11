<?php

use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $this->deleteJson("/api/projects/{$project->id}/milestones/{$milestone->id}")
        ->assertUnauthorized();
});

it('deletes a milestone owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->deleteJson("/api/projects/{$project->id}/milestones/{$milestone->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('project_milestone', ['id' => $milestone->id]);
});

it('forbids deleting a milestone owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $this->actingAs($customer, 'customer')
        ->deleteJson("/api/projects/{$project->id}/milestones/{$milestone->id}")
        ->assertForbidden();

    $this->assertDatabaseHas('project_milestone', ['id' => $milestone->id]);
});

it('cascades deletion when the parent project is removed', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    ProjectMilestone::factory()->count(3)->forProject($project)->create();

    $project->delete();

    expect(ProjectMilestone::where('project_id', $project->id)->count())->toBe(0);
});
