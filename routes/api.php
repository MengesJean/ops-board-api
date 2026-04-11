<?php

use App\Http\Controllers\Api\Auth\LoginController;
use App\Http\Controllers\Api\Auth\LogoutController;
use App\Http\Controllers\Api\Auth\MeController;
use App\Http\Controllers\Api\Auth\RegisterController;
use App\Http\Controllers\Api\Clients\ClientController;
use App\Http\Controllers\Api\Dashboard\DashboardController;
use App\Http\Controllers\Api\ProjectMilestones\ProjectMilestoneController;
use App\Http\Controllers\Api\Projects\ProjectActivityController;
use App\Http\Controllers\Api\Projects\ProjectController;
use App\Http\Controllers\Api\Projects\ProjectProgressController;
use App\Http\Controllers\Api\Tasks\TaskController;
use Illuminate\Support\Facades\Route;

Route::post('register', RegisterController::class)->name('customer.register');
Route::post('login', LoginController::class)->name('customer.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('me', MeController::class)->name('customer.me');
    Route::post('logout', LogoutController::class)->name('customer.logout');

    Route::get('dashboard', DashboardController::class)->name('dashboard');

    Route::apiResource('clients', ClientController::class);

    // Pilotage endpoints (progression + activity timeline). Declared *before*
    // the projects apiResource so the literal `progress` / `activity` segments
    // are never mistaken for a {project} model binding identifier.
    Route::get('projects/{project}/progress', ProjectProgressController::class)
        ->name('projects.progress');
    Route::get('projects/{project}/activity', ProjectActivityController::class)
        ->name('projects.activity');

    Route::apiResource('projects', ProjectController::class);

    // The reorder route is declared *before* the apiResource so that the
    // literal `reorder` segment is not captured as a {milestone} parameter.
    Route::patch('projects/{project}/milestones/reorder', [ProjectMilestoneController::class, 'reorder'])
        ->name('projects.milestones.reorder');

    Route::apiResource('projects.milestones', ProjectMilestoneController::class)
        ->parameters(['milestones' => 'milestone'])
        ->scoped(['milestone' => 'id']);

    // The reorder route is declared *before* the apiResource so that the
    // literal `reorder` segment is not captured as a {task} parameter.
    Route::patch('projects/{project}/tasks/reorder', [TaskController::class, 'reorder'])
        ->name('projects.tasks.reorder');

    Route::apiResource('projects.tasks', TaskController::class)
        ->parameters(['tasks' => 'task'])
        ->scoped(['task' => 'id']);
});
