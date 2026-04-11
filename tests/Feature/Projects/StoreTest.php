<?php

use App\Models\Client;
use App\Models\Customer;
use App\Models\Project;

it('rejects a guest', function () {
    $this->postJson('/api/projects', [])->assertUnauthorized();
});

it('creates a project under one of the authenticated customer\'s clients', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create();

    $payload = [
        'client_id' => $client->id,
        'name' => 'Acme website redesign',
        'reference' => 'PRJ-2026-001',
        'description' => 'Full marketing site redesign.',
        'status' => 'planned',
        'priority' => 'high',
        'health' => 'good',
        'start_date' => '2026-05-01',
        'due_date' => '2026-09-30',
        'budget' => 25000,
        'notes' => 'Kick-off scheduled.',
    ];

    $this->actingAs($customer, 'customer')
        ->postJson('/api/projects', $payload)
        ->assertCreated()
        ->assertJsonPath('data.name', 'Acme website redesign')
        ->assertJsonPath('data.client_id', $client->id)
        ->assertJsonPath('data.status', 'planned')
        ->assertJsonPath('data.priority', 'high')
        ->assertJsonPath('data.health', 'good')
        ->assertJsonPath('data.client.id', $client->id);

    $this->assertDatabaseHas('project', [
        'client_id' => $client->id,
        'name' => 'Acme website redesign',
        'status' => 'planned',
    ]);
});

it('rejects a client_id owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $foreignClient = Client::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->postJson('/api/projects', [
            'client_id' => $foreignClient->id,
            'name' => 'Hijack attempt',
            'status' => 'draft',
            'priority' => 'low',
            'health' => 'good',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('client_id');

    expect(Project::where('client_id', $foreignClient->id)->count())->toBe(0);
});

it('rejects an invalid payload', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->postJson('/api/projects', [
            'client_id' => null,
            'name' => '',
            'status' => 'nope',
            'priority' => 'whatever',
            'health' => 'meh',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['client_id', 'name', 'status', 'priority', 'health']);
});

it('rejects a due_date before start_date', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->postJson('/api/projects', [
            'client_id' => $client->id,
            'name' => 'Bad timeline',
            'status' => 'draft',
            'priority' => 'low',
            'health' => 'good',
            'start_date' => '2026-06-01',
            'due_date' => '2026-05-01',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('due_date');
});

it('rejects a negative budget', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->postJson('/api/projects', [
            'client_id' => $client->id,
            'name' => 'Bad budget',
            'status' => 'draft',
            'priority' => 'low',
            'health' => 'good',
            'budget' => -100,
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('budget');
});
