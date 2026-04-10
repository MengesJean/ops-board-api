<?php

use App\Models\Customer;

it('returns the authenticated customer', function () {
    $customer = Customer::factory()->create([
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
    ]);

    $this->actingAs($customer, 'customer')
        ->getJson('/api/me')
        ->assertOk()
        ->assertJsonPath('data.id', $customer->id)
        ->assertJsonPath('data.name', 'Ada Lovelace')
        ->assertJsonPath('data.email', 'ada@example.com')
        ->assertJsonMissingPath('data.password');
});

it('rejects a guest', function () {
    $this->getJson('/api/me')->assertUnauthorized();
});
