<?php

use App\Models\Customer;
use App\Models\Project;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $this->getJson("/api/projects/{$project->id}")->assertUnauthorized();
});

it('returns a project owned (via client) by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create(['name' => 'Mine']);

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $project->id)
        ->assertJsonPath('data.name', 'Mine')
        ->assertJsonPath('data.client.id', $project->client_id);
});

it('forbids accessing a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/projects/{$project->id}")
        ->assertForbidden();
});

it('returns 404 for a missing project', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/projects/999999')
        ->assertNotFound();
});
