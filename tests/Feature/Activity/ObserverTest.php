<?php

use App\Enums\MilestoneStatus;
use App\Enums\ProjectHealth;
use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;

it('records project.created with the owning customer and project ids', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $log = ActivityLog::query()
        ->where('subject_type', Project::class)
        ->where('subject_id', $project->id)
        ->where('event', 'project.created')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->customer_id)->toBe($customer->id);
    expect($log->project_id)->toBe($project->id);
});

it('records project.status_changed with from and to', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create([
        'status' => ProjectStatus::Planned,
    ]);

    $project->update(['status' => ProjectStatus::Active]);

    $log = ActivityLog::query()
        ->where('subject_type', Project::class)
        ->where('subject_id', $project->id)
        ->where('event', 'project.status_changed')
        ->first();

    expect($log)->not->toBeNull();
    expect($log->properties['from'])->toBe(ProjectStatus::Planned->value);
    expect($log->properties['to'])->toBe(ProjectStatus::Active->value);
});

it('records project.health_changed when health is dirty', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create([
        'health' => ProjectHealth::Good,
    ]);

    $project->update(['health' => ProjectHealth::Critical]);

    $log = ActivityLog::query()
        ->where('event', 'project.health_changed')
        ->where('subject_id', $project->id)
        ->first();

    expect($log)->not->toBeNull();
    expect($log->properties['to'])->toBe(ProjectHealth::Critical->value);
});

it('records task lifecycle events on create / update / complete', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();

    $task = Task::factory()->forProject($project)->todo()->create();

    $task->update(['status' => TaskStatus::InProgress]);
    $task->update(['status' => TaskStatus::Done]);

    $events = ActivityLog::query()
        ->where('subject_type', Task::class)
        ->where('subject_id', $task->id)
        ->orderBy('id')
        ->pluck('event')
        ->all();

    expect($events)->toContain('task.created');
    expect($events)->toContain('task.status_changed');
    expect($events)->toContain('task.started');
    expect($events)->toContain('task.completed');
});

it('records milestone attach / detach when a task is reassigned', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->create();

    $task = Task::factory()->forProject($project)->create();

    $task->update(['project_milestone_id' => $milestone->id]);
    $task->update(['project_milestone_id' => null]);

    $events = ActivityLog::query()
        ->where('subject_type', Task::class)
        ->where('subject_id', $task->id)
        ->orderBy('id')
        ->pluck('event')
        ->all();

    expect($events)->toContain('task.milestone_attached');
    expect($events)->toContain('task.milestone_detached');
});

it('records milestone.completed when status flips to done', function () {
    $customer = Customer::factory()->create();
    $project = Project::factory()->forCustomer($customer)->create();
    $milestone = ProjectMilestone::factory()->forProject($project)->pending()->create();

    $milestone->update(['status' => MilestoneStatus::Done]);

    $events = ActivityLog::query()
        ->where('subject_type', ProjectMilestone::class)
        ->where('subject_id', $milestone->id)
        ->pluck('event')
        ->all();

    expect($events)->toContain('milestone.status_changed');
    expect($events)->toContain('milestone.completed');
});

it('uses the authenticated customer as the actor when one is logged in', function () {
    $customer = Customer::factory()->create();
    $this->actingAs($customer, 'customer');

    $project = Project::factory()->forCustomer($customer)->create();

    $log = ActivityLog::query()
        ->where('subject_type', Project::class)
        ->where('subject_id', $project->id)
        ->where('event', 'project.created')
        ->first();

    expect($log->actor_type)->toBe(Customer::class);
    expect($log->actor_id)->toBe($customer->id);
});
