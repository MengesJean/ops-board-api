<?php

use App\Models\Client;
use App\Models\Customer;

it('rejects a guest', function () {
    $client = Client::factory()->create();
    $this->deleteJson("/api/clients/{$client->id}")->assertUnauthorized();
});

it('deletes a client owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $client = Client::factory()->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->deleteJson("/api/clients/{$client->id}")
        ->assertNoContent();

    $this->assertDatabaseMissing('client', ['id' => $client->id]);
});

it('forbids deleting a client owned by another customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();
    $client = Client::factory()->forCustomer($other)->create();

    $this->actingAs($customer, 'customer')
        ->deleteJson("/api/clients/{$client->id}")
        ->assertForbidden();

    $this->assertDatabaseHas('client', ['id' => $client->id]);
});

it('cascades deletion when the owner customer is removed', function () {
    $customer = Customer::factory()->create();
    Client::factory()->count(3)->forCustomer($customer)->create();

    $customer->delete();

    expect(Client::where('customer_id', $customer->id)->count())->toBe(0);
});
