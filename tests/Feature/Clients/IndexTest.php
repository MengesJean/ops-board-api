<?php

use App\Enums\ClientStatus;
use App\Models\Client;
use App\Models\Customer;

it('rejects a guest', function () {
    $this->getJson('/api/clients')->assertUnauthorized();
});

it('returns only clients owned by the authenticated customer', function () {
    $customer = Customer::factory()->create();
    $other = Customer::factory()->create();

    Client::factory()->count(2)->forCustomer($customer)->create();
    Client::factory()->count(3)->forCustomer($other)->create();

    $response = $this->actingAs($customer, 'customer')
        ->getJson('/api/clients')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(2);
    expect(collect($response->json('data'))->pluck('customer_id')->unique()->all())
        ->toEqual([$customer->id]);
});

it('paginates results', function () {
    $customer = Customer::factory()->create();
    Client::factory()->count(30)->forCustomer($customer)->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/clients?per_page=10')
        ->assertOk()
        ->assertJsonCount(10, 'data')
        ->assertJsonPath('meta.per_page', 10)
        ->assertJsonPath('meta.total', 30);
});

it('filters on search across name and company_name', function () {
    $customer = Customer::factory()->create();
    Client::factory()->forCustomer($customer)->create(['name' => 'Alpha Corp', 'company_name' => 'Alpha SA']);
    Client::factory()->forCustomer($customer)->create(['name' => 'Beta', 'company_name' => 'Acme']);
    Client::factory()->forCustomer($customer)->create(['name' => 'Gamma', 'company_name' => 'Delta']);

    $response = $this->actingAs($customer, 'customer')
        ->getJson('/api/clients?search=alpha')
        ->assertOk();

    expect($response->json('data'))->toHaveCount(1);
    expect($response->json('data.0.name'))->toBe('Alpha Corp');
});

it('filters by status', function () {
    $customer = Customer::factory()->create();
    Client::factory()->forCustomer($customer)->active()->count(2)->create();
    Client::factory()->forCustomer($customer)->lead()->count(3)->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/clients?status=active')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('validates filter params', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->getJson('/api/clients?status=nope')
        ->assertUnprocessable()
        ->assertJsonValidationErrors('status');
});
