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
})->skip(
    ! extension_loaded('dom'),
    'Scribe requires the DOM extension.',
);
