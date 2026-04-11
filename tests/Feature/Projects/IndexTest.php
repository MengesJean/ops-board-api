<?php

use App\Models\Client;
use App\Models\Customer;
use App\Models\Project;

it('rejects a guest', function () {
    $this->getJson('/api/projects')->assertUnauthorized();
});

it('returns only projects whose client belongs to the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();

    Project::factory()->count(2)->forCustomer($customer)->create();
    Project::factory()->count(3)->forCustomer($other)->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson('/api/projects')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(2);

    $clientIds = collect($response->json('data'))->pluck('client_id')->unique();
    $ownClientIds = $customer->clients()->pluck('id');

    expect($clientIds->diff($ownClientIds))->toBeEmpty();
});

it('paginates results', function () {
    $customer = Customer::factory()->create();
    Project::factory()->count(30)->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/projects?per_page=10')
        ->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('meta.per_page', 10)
        ->assertJsonPath('meta.total', 30);
});

it('filters by status', function () {
    $customer = Customer::factory()->create();
    Project::factory()->count(2)->active()->forCustomer($customer)->create();
    Project::factory()->count(3)->onHold()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/projects?status=active')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('filters by priority and health', function () {
    $customer = Customer::factory()->create();
    Project::factory()->count(2)->highPriority()->critical()->forCustomer($customer)->create();
    Project::factory()->count(4)->forCustomer($customer)->create([
        'priority' => 'low',
        'health' => 'good',
    ]);

    $this->actingAs($customer, 'customer')
        ->getJson('/api/projects?priority=high&health=critical')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('filters by client_id', function () {
    $customer = Customer::factory()->create();
    $clientA = Client::factory()->forCustomer($customer)->create();
    $clientB = Client::factory()->forCustomer($customer)->create();

    Project::factory()->count(2)->forClient($clientA)->create();
    Project::factory()->count(3)->forClient($clientB)->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/projects?client_id='.$clientA->id)
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('rejects a client_id owned by another customer in filters', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $foreignClient = Client::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/projects?client_id='.$foreignClient->id)
        ->assertUnprocessable()
        ->assertJsonValidationErrors('client_id');
});

it('searches across name and reference (case-insensitive)', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create();

    Project::factory()->forClient($client)->create(['name' => 'Alpha redesign', 'reference' => 'PRJ-001']);
    Project::factory()->forClient($client)->create(['name' => 'Beta launch', 'reference' => 'PRJ-002']);
    Project::factory()->forClient($client)->create(['name' => 'Gamma migration', 'reference' => 'XYZ-999']);

    $byName = $this->actingAs($customer, 'customer')
        ->getJson('/api/projects?search=alpha')
        ->assertOk();

    expect($byName->json('data'))->toHaveCount(1);
    expect($byName->json('data.0.name'))->toBe('Alpha redesign');

    $byReference = $this->actingAs($customer, 'customer')
        ->getJson('/api/projects?search=PRJ')
        ->assertOk();

    expect($byReference->json('data'))->toHaveCount(2);
});

it('sorts by due_date ascending', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create();

    Project::factory()->forClient($client)->create(['due_date' => '2026-09-01', 'name' => 'Late']);
    Project::factory()->forClient($client)->create(['due_date' => '2026-05-01', 'name' => 'Early']);
    Project::factory()->forClient($client)->create(['due_date' => '2026-07-01', 'name' => 'Mid']);

    $response = $this->actingAs($customer, 'customer')
        ->getJson('/api/projects?sort=due_date&direction=asc')
        ->assertOk();

    expect(collect($response->json('data'))->pluck('name')->all())
        ->toEqual(['Early', 'Mid', 'Late']);
});

it('validates filter params', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/projects?status=nope&sort=nonsense')
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['status', 'sort']);
});
