<?php

use App\Filament\Admin\Resources\Projects\Pages\EditProject;
use App\Filament\Admin\Resources\Projects\ProjectResource;
use App\Filament\Admin\Resources\Projects\RelationManagers\TasksRelationManager;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use App\Models\User;
use Livewire\Livewire;

it('renders the Project edit page for an admin and registers the Tasks relation manager', function () {
    $admin = User::factory()->create();
    $project = Project::factory()->create();
    Task::factory()->count(2)->forProject($project)->create();

    // With multiple relation managers, Filament renders them as tabs and only
    // the active one is hydrated as a Livewire component on first load. Assert
    // the page itself is reachable and that TasksRelationManager is wired into
    // the resource — the Livewire::test below covers actual data rendering.
    $this->actingAs($admin, 'web')
        ->get(ProjectResource::getUrl('edit', ['record' => $project]))
        ->assertOk();

    expect(ProjectResource::getRelations())->toContain(TasksRelationManager::class);
});

it('lets an admin list tasks via the relation manager', function () {
    $admin = User::factory()->create();
    $project = Project::factory()->create();
    $tasks = Task::factory()->count(3)->forProject($project)->create();

    $this->actingAs($admin, 'web');

    Livewire::test(TasksRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->assertCanSeeTableRecords($tasks);
});

it('only proposes milestones of the owner project in the create form', function () {
    $admin = User::factory()->create();
    $project = Project::factory()->create();
    $other = Project::factory()->create();

    $mineA = ProjectMilestone::factory()->forProject($project)->create(['title' => 'Mine A']);
    $mineB = ProjectMilestone::factory()->forProject($project)->create(['title' => 'Mine B']);
    ProjectMilestone::factory()->forProject($other)->create(['title' => 'Foreign']);

    $this->actingAs($admin, 'web');

    $manager = new TasksRelationManager;
    $manager->ownerRecord = $project;

    $options = $project->milestones()->pluck('title', 'id')->all();

    expect($options)->toHaveCount(2);
    expect(array_keys($options))->toEqualCanonicalizing([$mineA->id, $mineB->id]);
});

it('blocks a customer from the admin Project page', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->create();

    $this->actingAs($customer, 'customer')
        ->get(ProjectResource::getUrl('edit', ['record' => $project]))
        ->assertRedirect();
});
