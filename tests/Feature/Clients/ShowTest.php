<?php

use App\Models\Client;
use App\Models\Customer;

it('rejects a guest', function () {
    $client = Client::factory()->create();
    $this->getJson("/api/clients/{$client->id}")->assertUnauthorized();
});

it('returns a client owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create(['name' => 'Ada']);

    $this->actingAs($customer, 'customer')
        ->getJson("/api/clients/{$client->id}")
        ->assertOk()
        ->assertJsonPath('data.id', $client->id)
        ->assertJsonPath('data.name', 'Ada');
});

it('forbids accessing a client owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $client = Client::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->getJson("/api/clients/{$client->id}")
        ->assertForbidden();
});

it('returns 404 for a missing client', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/clients/999999')
        ->assertNotFound();
});
