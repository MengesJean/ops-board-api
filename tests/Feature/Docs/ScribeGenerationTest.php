<?php

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

it('generates the Scribe documentation for the customer auth endpoints', function () {
    // Use the `static` doc type during the test so we can assert a file
    // artifact exists, and place it under a disposable path.
    $outputPath = base_path('storage/framework/testing/scribe-docs');
    File::ensureDirectoryExists($outputPath);

    config()->set('scribe.type', 'static');
    config()->set('scribe.static.output_path', 'storage/framework/testing/scribe-docs');

    $exitCode = Artisan::call('scribe:generate', ['--no-upgrade-check' => true]);

    expect($exitCode)->toBe(0);

    $indexPath = base_path('storage/framework/testing/scribe-docs/index.html');
    expect(File::exists($indexPath))->toBeTrue();

    $markdown = File::get(base_path('.scribe/endpoints/00.yaml'));
    expect($markdown)
        ->toContain('api/register')
        ->toContain('api/login')
        ->toContain('api/logout')
        ->toContain('api/me');

    // Project Milestones live in their own group file; assert that all
    // milestone endpoints surfaced and the dedicated group is documented.
    $endpointFiles = File::files(base_path('.scribe/endpoints'));
    $milestonesYaml = collect($endpointFiles)
        ->map(fn ($file): string => File::get($file->getPathname()))
        ->first(fn (string $contents): bool => str_contains($contents, 'Project Milestones'));

    expect($milestonesYaml)
        ->not->toBeNull()
        ->and($milestonesYaml)
        ->toContain('api/projects/{project_id}/milestones')
        ->toContain('api/projects/{project_id}/milestones/{id}')
        ->toContain('api/projects/{project_id}/milestones/reorder');

    // Project Tasks live in their own group file; assert that all task
    // endpoints surfaced and the dedicated group is documented.
    $tasksYaml = collect($endpointFiles)
        ->map(fn ($file): string => File::get($file->getPathname()))
        ->first(fn (string $contents): bool => str_contains($contents, 'Project Tasks'));

    expect($tasksYaml)
        ->not->toBeNull()
        ->and($tasksYaml)
        ->toContain('api/projects/{project_id}/tasks')
        ->toContain('api/projects/{project_id}/tasks/{id}')
        ->toContain('api/projects/{project_id}/tasks/reorder');
})->skip(
    ! extension_loaded('dom'),
    'Scribe requires the DOM extension.',
);
