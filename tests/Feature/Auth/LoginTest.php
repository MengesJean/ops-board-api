<?php

use App\Models\Customer;

it('logs a customer in with valid credentials', function () {
    $customer = Customer::factory()->create([
        'email' => 'ada@example.com',
    ]);

    $this->postJson('/api/login', [
        'email' => 'ada@example.com',
        'password' => 'password',
    ])
        ->assertOk()
        ->assertJsonPath('data.id', $customer->id)
        ->assertJsonPath('data.email', 'ada@example.com');

    expect(auth('customer')->check())->toBeTrue();
    expect(auth('customer')->id())->toBe($customer->id);
});

it('rejects a login with an unknown email', function () {
    $this->postJson('/api/login', [
        'email' => 'nobody@example.com',
        'password' => 'password',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    expect(auth('customer')->check())->toBeFalse();
});

it('rejects a login with a wrong password', function () {
    Customer::factory()->create(['email' => 'ada@example.com']);

    $this->postJson('/api/login', [
        'email' => 'ada@example.com',
        'password' => 'nope',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);

    expect(auth('customer')->check())->toBeFalse();
});

it('requires email and password', function () {
    $this->postJson('/api/login', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email', 'password']);
});
