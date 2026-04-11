<?php

use App\Filament\Admin\Resources\Projects\ProjectResource;
use App\Models\Customer;
use App\Models\Project;
use App\Models\User;

it('lets an admin user access the Projects resource index', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin, 'web')
        ->get(ProjectResource::getUrl('index'))
        ->assertOk();
});

it('lets an admin user access the Projects create page', function () {
    $admin = User::factory()->create();

    $this->actingAs($admin, 'web')
        ->get(ProjectResource::getUrl('create'))
        ->assertOk();
});

it('lets an admin user access the Projects edit page', function () {
    $admin = User::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($admin, 'web')
        ->get(ProjectResource::getUrl('edit', ['record' => $project]))
        ->assertOk();
});

it('blocks a guest from the admin Projects resource', function () {
    $this->get(ProjectResource::getUrl('index'))
        ->assertRedirect();
});

it('blocks a customer from the admin panel', function () {
    $customer = Customer::factory()->create();

    $this->actingAs($customer, 'customer')
        ->get(ProjectResource::getUrl('index'))
        ->assertRedirect();
});
