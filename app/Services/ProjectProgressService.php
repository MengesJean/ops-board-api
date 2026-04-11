<?php

namespace App\Services;

use App\Enums\MilestoneStatus;
use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class ProjectProgressService
{
    /**
     * Compute the global progression of a project.
     *
     * Source of truth = the project's tasks. Milestones contribute counts only.
     *
     * @return array{
     *     total_tasks: int,
     *     todo_tasks: int,
     *     in_progress_tasks: int,
     *     completed_tasks: int,
     *     overdue_tasks: int,
     *     completion_rate: float,
     *     has_tasks: bool,
     *     total_milestones: int,
     *     completed_milestones: int,
     *     next_due_task: array{id:int,title:string,due_date:?string}|null,
     *     next_due_milestone: array{id:int,title:string,due_date:?string}|null,
     *     is_overdue: bool
     * }
     */
    public function forProject(Project $project): array
    {
        // Use the query builder (not Eloquent) so model casts don't turn the
        // raw `status` string back into a TaskStatus enum and break the
        // status-keyed lookup below.
        $taskCounts = DB::table('task')
            ->where('project_id', $project->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $todo = (int) ($taskCounts[TaskStatus::Todo->value] ?? 0);
        $inProgress = (int) ($taskCounts[TaskStatus::InProgress->value] ?? 0);
        $done = (int) ($taskCounts[TaskStatus::Done->value] ?? 0);
        $totalTasks = $todo + $inProgress + $done;

        $today = Carbon::today();

        $overdueTasks = Task::query()
            ->where('project_id', $project->id)
            ->where('status', '!=', TaskStatus::Done->value)
            ->whereNotNull('due_date')
            ->where('due_date', '<', $today->toDateString())
            ->count();

        $milestoneCounts = DB::table('project_milestone')
            ->where('project_id', $project->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $totalMilestones = array_sum($milestoneCounts);
        $completedMilestones = (int) ($milestoneCounts[MilestoneStatus::Done->value] ?? 0);

        $nextDueTask = Task::query()
            ->where('project_id', $project->id)
            ->where('status', '!=', TaskStatus::Done->value)
            ->whereNotNull('due_date')
            ->orderBy('due_date')
            ->orderBy('id')
            ->first(['id', 'title', 'due_date']);

        $nextDueMilestone = ProjectMilestone::query()
            ->where('project_id', $project->id)
            ->where('status', '!=', MilestoneStatus::Done->value)
            ->whereNotNull('due_date')
            ->orderBy('due_date')
            ->orderBy('id')
            ->first(['id', 'title', 'due_date']);

        $isOverdue = $project->due_date !== null
            && $project->due_date->lt($today)
            && ! in_array($project->status, [ProjectStatus::Completed, ProjectStatus::Cancelled], true);

        $hasTasks = $totalTasks > 0;
        $completionRate = $hasTasks ? round($done / $totalTasks, 4) : 0.0;

        return [
            'total_tasks' => $totalTasks,
            'todo_tasks' => $todo,
            'in_progress_tasks' => $inProgress,
            'completed_tasks' => $done,
            'overdue_tasks' => $overdueTasks,
            'completion_rate' => $completionRate,
            'has_tasks' => $hasTasks,
            'total_milestones' => (int) $totalMilestones,
            'completed_milestones' => $completedMilestones,
            'next_due_task' => $nextDueTask ? [
                'id' => $nextDueTask->id,
                'title' => $nextDueTask->title,
                'due_date' => $nextDueTask->due_date?->toDateString(),
            ] : null,
            'next_due_milestone' => $nextDueMilestone ? [
                'id' => $nextDueMilestone->id,
                'title' => $nextDueMilestone->title,
                'due_date' => $nextDueMilestone->due_date?->toDateString(),
            ] : null,
            'is_overdue' => $isOverdue,
        ];
    }

    /**
     * Compute task stats for a single milestone.
     *
     * Returns a `completion_rate` of `null` when the milestone has no tasks,
     * so the caller can distinguish "no tasks yet" from "0% done".
     *
     * @return array{total_tasks:int, completed_tasks:int, completion_rate:?float}
     */
    public function forMilestone(ProjectMilestone $milestone): array
    {
        $counts = DB::table('task')
            ->where('project_milestone_id', $milestone->id)
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status')
            ->toArray();

        $total = (int) array_sum($counts);
        $done = (int) ($counts[TaskStatus::Done->value] ?? 0);

        return [
            'total_tasks' => $total,
            'completed_tasks' => $done,
            'completion_rate' => $total > 0 ? round($done / $total, 4) : null,
        ];
    }

    /**
     * Compute global project progression + per-milestone breakdown in a
     * single bulk query for the milestones (no N+1).
     *
     * @return array{project: array<string, mixed>, milestones: list<array<string, mixed>>}
     */
    public function forProjectWithMilestones(Project $project): array
    {
        $projectStats = $this->forProject($project);

        $project->loadMissing('milestones');

        $rows = DB::table('task')
            ->where('project_id', $project->id)
            ->whereNotNull('project_milestone_id')
            ->selectRaw('project_milestone_id, status, COUNT(*) as total')
            ->groupBy('project_milestone_id', 'status')
            ->get();

        $perMilestone = [];
        foreach ($rows as $row) {
            $perMilestone[(int) $row->project_milestone_id][(string) $row->status] = (int) $row->total;
        }

        $milestones = $project->milestones->map(function (ProjectMilestone $milestone) use ($perMilestone): array {
            $counts = $perMilestone[$milestone->id] ?? [];
            $total = (int) array_sum($counts);
            $done = (int) ($counts[TaskStatus::Done->value] ?? 0);

            return [
                'id' => $milestone->id,
                'title' => $milestone->title,
                'status' => $milestone->status->value,
                'position' => $milestone->position,
                'due_date' => $milestone->due_date?->toDateString(),
                'completed_at' => $milestone->completed_at?->toIso8601String(),
                'total_tasks' => $total,
                'completed_tasks' => $done,
                'completion_rate' => $total > 0 ? round($done / $total, 4) : null,
            ];
        })->all();

        return [
            'project' => $projectStats,
            'milestones' => $milestones,
        ];
    }
}
