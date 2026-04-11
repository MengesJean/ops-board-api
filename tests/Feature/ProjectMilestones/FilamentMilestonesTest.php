<?php

use App\Filament\Admin\Resources\Projects\Pages\EditProject;
use App\Filament\Admin\Resources\Projects\ProjectResource;
use App\Filament\Admin\Resources\Projects\RelationManagers\MilestonesRelationManager;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\User;
use Livewire\Livewire;

it('shows the Milestones relation manager on the Project edit page for an admin', function () {
    $admin = User::factory()->create();
    $project = Project::factory()->create();
    ProjectMilestone::factory()->count(2)->forProject($project)->create();

    $this->actingAs($admin, 'web')
        ->get(ProjectResource::getUrl('edit', ['record' => $project]))
        ->assertOk()
        ->assertSeeLivewire(MilestonesRelationManager::class);
});

it('lets an admin list milestones via the relation manager', function () {
    $admin = User::factory()->create();
    $project = Project::factory()->create();
    $milestones = ProjectMilestone::factory()->count(3)->forProject($project)->create();

    $this->actingAs($admin, 'web');

    Livewire::test(MilestonesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->assertCanSeeTableRecords($milestones);
});

it('blocks a customer from the admin Project page', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($customer, 'customer')
        ->get(ProjectResource::getUrl('edit', ['record' => $project]))
        ->assertRedirect();
});
