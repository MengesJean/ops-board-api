<?php

use App\Models\Client;
use App\Models\Customer;

it('rejects a guest', function () {
    $client = Client::factory()->create();
    $this->putJson("/api/clients/{$client->id}", [])->assertUnauthorized();
});

it('updates a client owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->lead()->create();

    $this->actingAs($customer, 'customer')
        ->putJson("/api/clients/{$client->id}", [
            'name' => 'Updated name',
            'email' => 'updated@test.test',
            'status' => 'active',
            'notes' => 'Upgraded.',
        ])
        ->assertOk()
        ->assertJsonPath('data.name', 'Updated name')
        ->assertJsonPath('data.status', 'active')
        ->assertJsonPath('data.notes', 'Upgraded.');

    $this->assertDatabaseHas('client', [
        'id' => $client->id,
        'name' => 'Updated name',
        'status' => 'active',
    ]);
});

it('forbids updating a client owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $client = Client::factory()->forCustomer($other)->create(['name' => 'Untouched']);

    $this->actingAs($customer, 'customer')
        ->putJson("/api/clients/{$client->id}", [
            'name' => 'Hijacked',
            'email' => 'hijack@test.test',
            'status' => 'active',
        ])
        ->assertForbidden();

    expect($client->fresh()->name)->toBe('Untouched');
});

it('rejects invalid update payload', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->putJson("/api/clients/{$client->id}", [
            'name' => '',
            'email' => 'not-an-email',
            'status' => 'invalid',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'status']);
});

it('allows keeping the same email on update', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create(['email' => 'stay@test.test']);

    $this->actingAs($customer, 'customer')
        ->putJson("/api/clients/{$client->id}", [
            'name' => 'Still me',
            'email' => 'stay@test.test',
            'status' => 'active',
        ])
        ->assertOk();
});
