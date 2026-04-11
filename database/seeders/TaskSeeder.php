<?php

namespace Database\Seeders;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    /**
     * Catalogue of tasks per milestone title. Each entry is realistic for the
     * milestone it sits under so the seeded data tells a coherent story when
     * the dashboard is browsed.
     *
     * @var array<string, list<array{title: string, status: TaskStatus, priority: TaskPriority, due_in_weeks?: int}>>
     */
    private const PLAYBOOK = [
        'Discovery & scoping' => [
            ['title' => 'Stakeholder interviews', 'status' => TaskStatus::Done, 'priority' => TaskPriority::High],
            ['title' => 'Document business goals', 'status' => TaskStatus::Done, 'priority' => TaskPriority::Medium],
            ['title' => 'Draft scope statement', 'status' => TaskStatus::Done, 'priority' => TaskPriority::High],
        ],
        'Design sign-off' => [
            ['title' => 'Wireframe key flows', 'status' => TaskStatus::Done, 'priority' => TaskPriority::High],
            ['title' => 'Hi-fi mockup of homepage', 'status' => TaskStatus::Done, 'priority' => TaskPriority::Medium],
            ['title' => 'Client review session', 'status' => TaskStatus::Done, 'priority' => TaskPriority::Medium],
        ],
        'Development' => [
            ['title' => 'Set up CI/CD pipeline', 'status' => TaskStatus::Done, 'priority' => TaskPriority::Medium],
            ['title' => 'Implement authentication', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::High, 'due_in_weeks' => 1],
            ['title' => 'Build dashboard layout', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::Medium, 'due_in_weeks' => 2],
            ['title' => 'Wire up the API client', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::Medium, 'due_in_weeks' => 3],
            ['title' => 'Cover critical paths with tests', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::High, 'due_in_weeks' => 3],
        ],
        'Client UAT' => [
            ['title' => 'Prepare UAT script', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::Medium, 'due_in_weeks' => 5],
            ['title' => 'Walk client through the staging build', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::High, 'due_in_weeks' => 6],
            ['title' => 'Triage feedback and assign fixes', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::High, 'due_in_weeks' => 7],
        ],
        'Production launch' => [
            ['title' => 'Final regression pass', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::High, 'due_in_weeks' => 9],
            ['title' => 'Deploy v1 to production', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::High, 'due_in_weeks' => 10],
            ['title' => 'Post-launch monitoring window', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::Medium, 'due_in_weeks' => 11],
        ],
    ];

    /**
     * Tasks created on every project without a milestone, so the "no milestone"
     * UI state is exercised by seeded data too.
     *
     * @var list<array{title: string, status: TaskStatus, priority: TaskPriority}>
     */
    private const STANDALONE = [
        ['title' => 'Weekly project sync', 'status' => TaskStatus::InProgress, 'priority' => TaskPriority::Low],
        ['title' => 'Update the project README', 'status' => TaskStatus::Todo, 'priority' => TaskPriority::Low],
    ];

    public function run(): void
    {
        Project::with('milestones')->get()->each(function (Project $project): void {
            $project->milestones->each(function (ProjectMilestone $milestone): void {
                $playbook = self::PLAYBOOK[$milestone->title] ?? [];

                foreach ($playbook as $entry) {
                    Task::factory()
                        ->forMilestone($milestone)
                        ->create([
                            'title' => $entry['title'],
                            'status' => $entry['status'],
                            'priority' => $entry['priority'],
                            'due_date' => isset($entry['due_in_weeks'])
                                ? now()->addWeeks($entry['due_in_weeks'])->toDateString()
                                : null,
                        ]);
                }
            });

            foreach (self::STANDALONE as $entry) {
                Task::factory()
                    ->forProject($project)
                    ->create([
                        'title' => $entry['title'],
                        'status' => $entry['status'],
                        'priority' => $entry['priority'],
                    ]);
            }
        });
    }
}
