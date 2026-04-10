<?php

use App\Models\Customer;

it('logs the authenticated customer out', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->postJson('/api/logout')
        ->assertNoContent();

    expect(auth('customer')->check())->toBeFalse();
});

it('refuses to log out a guest', function () {
    $this->postJson('/api/logout')->assertUnauthorized();
});
