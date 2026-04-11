<?php

namespace Database\Seeders;

use App\Enums\MilestoneStatus;
use App\Models\Project;
use App\Models\ProjectMilestone;
use Illuminate\Database\Seeder;

class ProjectMilestoneSeeder extends Seeder
{
    /**
     * @var list<array{title: string, status: MilestoneStatus}>
     */
    private const ROADMAP = [
        ['title' => 'Discovery & scoping', 'status' => MilestoneStatus::Done],
        ['title' => 'Design sign-off', 'status' => MilestoneStatus::Done],
        ['title' => 'Development', 'status' => MilestoneStatus::InProgress],
        ['title' => 'Client UAT', 'status' => MilestoneStatus::Pending],
        ['title' => 'Production launch', 'status' => MilestoneStatus::Pending],
    ];

    public function run(): void
    {
        Project::all()->each(function (Project $project): void {
            foreach (self::ROADMAP as $index => $milestone) {
                ProjectMilestone::factory()
                    ->forProject($project)
                    ->create([
                        'title' => $milestone['title'],
                        'status' => $milestone['status'],
                        'due_date' => now()->addWeeks(($index + 1) * 3)->toDateString(),
                    ]);
            }
        });
    }
}
