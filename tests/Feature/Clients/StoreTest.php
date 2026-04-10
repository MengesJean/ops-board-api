<?php

use App\Models\Client;
use App\Models\Customer;

it('rejects a guest', function () {
    $this->postJson('/api/clients', [])->assertUnauthorized();
});

it('creates a client attached to the authenticated customer', function () {
    $customer = Customer::factory()->create();

    $payload = [
        'name' => 'Grace Hopper',
        'company_name' => 'Hopper Industries',
        'email' => 'grace@hopper.test',
        'phone' => '+1 555 0123',
        'status' => 'lead',
        'notes' => 'Met at Q2 conference.',
    ];

    $this->actingAs($customer, 'customer')
        ->postJson('/api/clients', $payload)
        ->assertCreated()
        ->assertJsonPath('data.name', 'Grace Hopper')
        ->assertJsonPath('data.customer_id', $customer->id)
        ->assertJsonPath('data.status', 'lead');

    $this->assertDatabaseHas('client', [
        'customer_id' => $customer->id,
        'email' => 'grace@hopper.test',
    ]);
});

it('ignores customer_id from the payload and uses the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->postJson('/api/clients', [
            'customer_id' => $other->id,
            'name' => 'Impersonation attempt',
            'email' => 'x@y.test',
            'status' => 'lead',
        ])
        ->assertCreated()
        ->assertJsonPath('data.customer_id', $customer->id);

    $this->assertDatabaseHas('client', [
        'customer_id' => $customer->id,
        'email' => 'x@y.test',
    ]);
});

it('rejects invalid payload', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->postJson('/api/clients', [
            'name' => '',
            'email' => 'not-an-email',
            'status' => 'wrong',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'status']);
});

it('allows the same email across different customers', function () {
    $a = Customer::factory()->create();
    $b = Customer::factory()->create();

    Client::factory()->forCustomer($a)->create(['email' => 'shared@test.test']);

    $this->actingAs($b, 'customer')
        ->postJson('/api/clients', [
            'name' => 'B Client',
            'email' => 'shared@test.test',
            'status' => 'active',
        ])
        ->assertCreated();
});

it('rejects duplicate email for the same customer', function () {
    $customer = Customer::factory()->create();
    Client::factory()->forCustomer($customer)->create(['email' => 'dup@test.test']);

    $this->actingAs($customer, 'customer')
        ->postJson('/api/clients', [
            'name' => 'Dup',
            'email' => 'dup@test.test',
            'status' => 'lead',
        ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors('email');
});
