<?php

use App\Models\Client;
use App\Models\Customer;
use App\Models\Project;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $this->deleteJson("/api/projects/{$project->id}")->assertUnauthorized();
});

it('deletes a project owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->deleteJson("/api/projects/{$project->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('project', ['id' => $project->id]);
});

it('forbids deleting a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->deleteJson("/api/projects/{$project->id}")
        ->assertForbidden();

    $this->assertDatabaseHas('project', ['id' => $project->id]);
});

it('cascades deletion when the parent client is removed', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create();
    Project::factory()->count(3)->forClient($client)->create();

    $client->delete();

    expect(Project::where('client_id', $client->id)->count())->toBe(0);
});
