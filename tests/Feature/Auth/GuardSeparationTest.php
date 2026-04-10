<?php

use App\Models\Customer;
use App\Models\User;

it('writes customers to the customer table only', function () {
    Customer::factory()->create(['email' => 'ada@example.com']);

    $this->assertDatabaseHas('customer', ['email' => 'ada@example.com']);
    $this->assertDatabaseMissing('user', ['email' => 'ada@example.com']);
});

it('writes admins to the user table only', function () {
    User::factory()->create(['email' => 'admin@opsboard.test']);

    $this->assertDatabaseHas('user', ['email' => 'admin@opsboard.test']);
    $this->assertDatabaseMissing('customer', ['email' => 'admin@opsboard.test']);
});

it('rejects an admin trying to authenticate via the customer login', function () {
    User::factory()->create([
        'email' => 'admin@opsboard.test',
    ]);

    $this->postJson('/api/login', [
        'email' => 'admin@opsboard.test',
        'password' => 'password',
    ])->assertUnprocessable();

    expect(auth('customer')->check())->toBeFalse();
});

it('does not let a web-guard admin pass auth:sanctum on /api/me', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin, 'web')
        ->getJson('/api/me')
        ->assertUnauthorized();
});

it('does not let a customer be recognised on the web (admin) guard', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer');

    expect(auth('customer')->check())->toBeTrue();
    expect(auth('web')->check())->toBeFalse();
});
