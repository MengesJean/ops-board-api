<?php

use App\Models\Customer;
use Illuminate\Support\Facades\Hash;

it('creates a customer and starts a session', function () {
    $response = $this->postJson('/api/register', [
        'name' => 'Ada Lovelace',
        'email' => 'ada@example.com',
        'password' => 'correct-horse-battery',
        'password_confirmation' => 'correct-horse-battery',
    ]);

    $response->assertCreated()
        ->assertJsonPath('data.email', 'ada@example.com')
        ->assertJsonMissingPath('data.password');

    $customer = Customer::query()->where('email', 'ada@example.com')->first();
    expect($customer)->not->toBeNull();
    expect(Hash::check('correct-horse-battery', $customer->password))->toBeTrue();

    expect(auth('customer')->check())->toBeTrue();
    expect(auth('customer')->id())->toBe($customer->id);
});

it('rejects missing fields', function () {
    $this->postJson('/api/register', [])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['name', 'email', 'password']);
});

it('rejects an already-used email', function () {
    Customer::factory()->create(['email' => 'taken@example.com']);

    $this->postJson('/api/register', [
        'name' => 'Bob',
        'email' => 'taken@example.com',
        'password' => 'correct-horse-battery',
        'password_confirmation' => 'correct-horse-battery',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['email']);
});

it('requires the password to be confirmed', function () {
    $this->postJson('/api/register', [
        'name' => 'Ada',
        'email' => 'ada@example.com',
        'password' => 'correct-horse-battery',
        'password_confirmation' => 'mismatch',
    ])
        ->assertUnprocessable()
        ->assertJsonValidationErrors(['password']);
});
