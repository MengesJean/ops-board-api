<?php

use App\Models\Customer;
use App\Models\User;

it('redirects guests to the Filament login page', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');
});

it('lets an authenticated admin reach the panel', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin, 'web')
        ->get('/admin')
        ->assertOk();
});

it('keeps customers out of the admin panel', function () {
    $customer = Customer::factory()->create();

    // A Customer is not authenticated on the `web` guard, so Filament's
    // auth middleware treats them as a guest.
    $this->actingAs($customer, 'customer')
        ->get('/admin')
        ->assertRedirect('/admin/login');
});
