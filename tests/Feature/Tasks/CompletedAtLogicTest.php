<?php

use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\Task;

it('stamps completed_at when a task is created in done status', function () {
    $project = Project::factory()->create();

    $task = Task::factory()
        ->forProject($project)
        ->done()
        ->create();

    expect($task->completed_at)->not->toBeNull();
});

it('does not stamp completed_at on a non-done create', function () {
    $project = Project::factory()->create();

    $task = Task::factory()
        ->forProject($project)
        ->inProgress()
        ->create();

    expect($task->completed_at)->toBeNull();
});

it('stamps completed_at when status transitions to done', function () {
    $task = Task::factory()->todo()->create();
    expect($task->completed_at)->toBeNull();

    $task->update(['status' => TaskStatus::Done]);

    expect($task->fresh()->completed_at)->not->toBeNull();
});

it('clears completed_at when status leaves done', function () {
    $task = Task::factory()->done()->create();
    expect($task->fresh()->completed_at)->not->toBeNull();

    $task->update(['status' => TaskStatus::InProgress]);

    expect($task->fresh()->completed_at)->toBeNull();
});

it('preserves completed_at when re-saving without status change', function () {
    $task = Task::factory()->done()->create();
    $stamp = $task->fresh()->completed_at;

    $task->update(['title' => 'Renamed']);

    expect($task->fresh()->completed_at?->toIso8601String())
        ->toBe($stamp?->toIso8601String());
});
