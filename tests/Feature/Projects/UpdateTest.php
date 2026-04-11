<?php

use App\Models\Client;
use App\Models\Customer;
use App\Models\Project;

it('rejects a guest', function () {
    $project = Project::factory()->create();
    $this->putJson("/api/projects/{$project->id}", [])->assertUnauthorized();
});

it('updates a project owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create(['status' => 'draft']);

    $this->actingAs($customer, 'customer')
        ->putJson("/api/projects/{$project->id}", [
            'name' => 'Updated name',
            'status' => 'active',
            'priority' => 'high',
            'health' => 'warning',
            'notes' => 'Pushed go-live.',
        ])
        ->assertOk()
        ->assertJsonPath('data.name', 'Updated name')
        ->assertJsonPath('data.status', 'active')
        ->assertJsonPath('data.health', 'warning');

    $this->assertDatabaseHas('project', [
        'id' => $project->id,
        'name' => 'Updated name',
        'status' => 'active',
    ]);
});

it('forbids updating a project owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $project = Project::factory()->forCustomer($other)->create(['name' => 'Untouched']);

    $this->actingAs($customer, 'customer')
        ->putJson("/api/projects/{$project->id}", [
            'name' => 'Hijacked',
            'status' => 'active',
            'priority' => 'high',
            'health' => 'good',
        ])
        ->assertForbidden();

    expect($project->fresh()->name)->toBe('Untouched');
});

it('rejects reassigning client_id to a client owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();

    $project = Project::factory()->forCustomer($customer)->create();
    $foreignClient = Client::factory()->forCustomer($other)->create();
    $originalClientId = $project->client_id;

    $this->actingAs($customer, 'customer')
        ->putJson("/api/projects/{$project->id}", [
            'client_id' => $foreignClient->id,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('client_id');

    expect($project->fresh()->client_id)->toBe($originalClientId);
});

it('rejects an invalid payload on update', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->putJson("/api/projects/{$project->id}", [
            'name' => '',
            'status' => 'invalid',
            'priority' => 'invalid',
            'health' => 'invalid',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'status', 'priority', 'health']);
});
