<?php

use App\Filament\Admin\Resources\Clients\ClientResource;
use App\Models\Client;
use App\Models\Customer;
use App\Models\User;

it('lets an admin user access the Clients resource index', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin, 'web')
        ->get(ClientResource::getUrl('index'))
        ->assertOk();
});

it('lets an admin user access the Clients create page', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin, 'web')
        ->get(ClientResource::getUrl('create'))
        ->assertOk();
});

it('lets an admin user access the Clients edit page', function () {
    $admin = User::factory()->create();
    $client = Client::factory()->create();

    $this->actingAs($admin, 'web')
        ->get(ClientResource::getUrl('edit', ['record' => $client]))
        ->assertOk();
});

it('blocks a guest from the admin Clients resource', function () {
    $this->get(ClientResource::getUrl('index'))
        ->assertRedirect();
});

it('blocks a customer from the admin panel', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->get(ClientResource::getUrl('index'))
        ->assertRedirect();
});
