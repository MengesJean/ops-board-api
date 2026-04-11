<?php

use App\Enums\MilestoneStatus;
use App\Models\Project;
use App\Models\ProjectMilestone;

it('stamps completed_at when a milestone is created in done status', function () {
    $project = Project::factory()->create();

    $milestone = ProjectMilestone::factory()
        ->forProject($project)
        ->done()
        ->create();

    expect($milestone->completed_at)->not->toBeNull();
});

it('does not stamp completed_at on a non-done create', function () {
    $project = Project::factory()->create();

    $milestone = ProjectMilestone::factory()
        ->forProject($project)
        ->inProgress()
        ->create();

    expect($milestone->completed_at)->toBeNull();
});

it('stamps completed_at when status transitions to done', function () {
    $milestone = ProjectMilestone::factory()->pending()->create();
    expect($milestone->completed_at)->toBeNull();

    $milestone->update(['status' => MilestoneStatus::Done]);

    expect($milestone->fresh()->completed_at)->not->toBeNull();
});

it('clears completed_at when status leaves done', function () {
    $milestone = ProjectMilestone::factory()->done()->create();
    expect($milestone->fresh()->completed_at)->not->toBeNull();

    $milestone->update(['status' => MilestoneStatus::InProgress]);

    expect($milestone->fresh()->completed_at)->toBeNull();
});

it('preserves completed_at when re-saving without status change', function () {
    $milestone = ProjectMilestone::factory()->done()->create();
    $stamp = $milestone->fresh()->completed_at;

    $milestone->update(['title' => 'Renamed']);

    expect($milestone->fresh()->completed_at?->toIso8601String())
        ->toBe($stamp?->toIso8601String());
});
