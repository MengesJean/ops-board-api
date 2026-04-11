<?php

namespace App\Services;

use App\Enums\MilestoneStatus;
use App\Enums\ProjectHealth;
use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Http\Resources\Activity\ActivityLogResource;
use App\Http\Resources\Dashboard\DashboardProjectResource;
use App\Http\Resources\Dashboard\DashboardTaskResource;
use App\Models\ActivityLog;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class DashboardService
{
    public function __construct(private ProjectProgressService $progress) {}

    /**
     * Build the full dashboard payload for a customer. Every read in this
     * service is rooted in `$customer->id`, so the result can never leak data
     * from another customer.
     *
     * @return array{
     *     stats: array<string, mixed>,
     *     priorities: array<string, mixed>,
     *     projects: array<int, array<string, mixed>>,
     *     recent_activity: array<int, array<string, mixed>>
     * }
     */
    public function buildFor(Customer $customer): array
    {
        return [
            'stats' => $this->stats($customer),
            'priorities' => $this->priorities($customer),
            'projects' => $this->projectsSummary($customer),
            'recent_activity' => $this->recentActivity($customer),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function stats(Customer $customer): array
    {
        $today = Carbon::today();

        $projectStatusCounts = $customer->projects()
            ->selectRaw('project.status as status, COUNT(*) as total')
            ->groupBy('project.status')
            ->pluck('total', 'status')
            ->toArray();

        $projectHealthCounts = $customer->projects()
            ->selectRaw('project.health as health, COUNT(*) as total')
            ->groupBy('project.health')
            ->pluck('total', 'health')
            ->toArray();

        $totalTasks = $this->customerTasksQuery($customer)->count();
        $doneTasks = $this->customerTasksQuery($customer)
            ->where('task.status', TaskStatus::Done->value)
            ->count();

        $overdueTasks = $this->customerTasksQuery($customer)
            ->where('task.status', '!=', TaskStatus::Done->value)
            ->whereNotNull('task.due_date')
            ->where('task.due_date', '<', $today->toDateString())
            ->count();

        $dueTodayTasks = $this->customerTasksQuery($customer)
            ->where('task.status', '!=', TaskStatus::Done->value)
            ->whereDate('task.due_date', $today->toDateString())
            ->count();

        $upcomingMilestones = $this->customerMilestonesQuery($customer)
            ->where('project_milestone.status', '!=', MilestoneStatus::Done->value)
            ->whereNotNull('project_milestone.due_date')
            ->whereBetween('project_milestone.due_date', [
                $today->toDateString(),
                $today->copy()->addDays(7)->toDateString(),
            ])
            ->count();

        return [
            'active_projects_count' => (int) ($projectStatusCounts[ProjectStatus::Active->value] ?? 0),
            'completed_projects_count' => (int) ($projectStatusCounts[ProjectStatus::Completed->value] ?? 0),
            'warning_projects_count' => (int) ($projectHealthCounts[ProjectHealth::Warning->value] ?? 0),
            'critical_projects_count' => (int) ($projectHealthCounts[ProjectHealth::Critical->value] ?? 0),
            'overdue_tasks_count' => $overdueTasks,
            'due_today_tasks_count' => $dueTodayTasks,
            'upcoming_milestones_count' => $upcomingMilestones,
            'global_completion_rate' => $totalTasks > 0 ? round($doneTasks / $totalTasks, 4) : 0.0,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function priorities(Customer $customer): array
    {
        $today = Carbon::today();

        $overdueTasks = $this->customerTasksQuery($customer)
            ->select('task.*')
            ->with(['project:id,name,client_id'])
            ->where('task.status', '!=', TaskStatus::Done->value)
            ->whereNotNull('task.due_date')
            ->where('task.due_date', '<', $today->toDateString())
            ->orderBy('task.due_date')
            ->limit(10)
            ->get();

        $dueTodayTasks = $this->customerTasksQuery($customer)
            ->select('task.*')
            ->with(['project:id,name,client_id'])
            ->where('task.status', '!=', TaskStatus::Done->value)
            ->whereDate('task.due_date', $today->toDateString())
            ->orderBy('task.priority')
            ->limit(10)
            ->get();

        $upcomingMilestones = $this->customerMilestonesQuery($customer)
            ->select('project_milestone.*')
            ->with(['project:id,name,client_id'])
            ->where('project_milestone.status', '!=', MilestoneStatus::Done->value)
            ->whereNotNull('project_milestone.due_date')
            ->whereBetween('project_milestone.due_date', [
                $today->toDateString(),
                $today->copy()->addDays(7)->toDateString(),
            ])
            ->orderBy('project_milestone.due_date')
            ->limit(10)
            ->get();

        $atRiskProjects = $customer->projects()
            ->with('client:id,name')
            ->whereIn('project.health', [ProjectHealth::Warning->value, ProjectHealth::Critical->value])
            ->whereNotIn('project.status', [ProjectStatus::Completed->value, ProjectStatus::Cancelled->value])
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn ($q) => $q->where('status', TaskStatus::Done->value),
            ])
            ->orderByRaw("CASE project.health WHEN 'critical' THEN 0 WHEN 'warning' THEN 1 ELSE 2 END")
            ->limit(5)
            ->get();

        return [
            'overdue_tasks' => DashboardTaskResource::collection($overdueTasks)->resolve(),
            'due_today_tasks' => DashboardTaskResource::collection($dueTodayTasks)->resolve(),
            'upcoming_milestones' => $upcomingMilestones->map(fn (ProjectMilestone $milestone): array => [
                'id' => $milestone->id,
                'title' => $milestone->title,
                'status' => $milestone->status->value,
                'due_date' => $milestone->due_date?->toDateString(),
                'project' => [
                    'id' => $milestone->project?->id,
                    'name' => $milestone->project?->name,
                ],
            ])->all(),
            'at_risk_projects' => DashboardProjectResource::collection($atRiskProjects)->resolve(),
        ];
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function projectsSummary(Customer $customer): array
    {
        $projects = $customer->projects()
            ->with('client:id,name')
            ->withCount([
                'tasks',
                'tasks as completed_tasks_count' => fn ($q) => $q->where('status', TaskStatus::Done->value),
            ])
            ->whereIn('project.status', [
                ProjectStatus::Active->value,
                ProjectStatus::Planned->value,
            ])
            ->orderByRaw('project.due_date IS NULL')
            ->orderBy('project.due_date')
            ->limit(8)
            ->get();

        return DashboardProjectResource::collection($projects)->resolve();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function recentActivity(Customer $customer): array
    {
        $activities = ActivityLog::query()
            ->forCustomer($customer->id)
            ->with(['subject', 'actor'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->limit(15)
            ->get();

        return ActivityLogResource::collection($activities)->resolve();
    }

    /**
     * Builds a Task query already scoped to the customer's project chain.
     * The join lives at the table level (no Eloquent relations) so the
     * resulting query is cheap and can be reused for COUNT and SELECT calls.
     *
     * @return Builder<Task>
     */
    private function customerTasksQuery(Customer $customer): Builder
    {
        return Task::query()
            ->join('project', 'project.id', '=', 'task.project_id')
            ->join('client', 'client.id', '=', 'project.client_id')
            ->where('client.customer_id', $customer->id);
    }

    /**
     * @return Builder<ProjectMilestone>
     */
    private function customerMilestonesQuery(Customer $customer): Builder
    {
        return ProjectMilestone::query()
            ->join('project', 'project.id', '=', 'project_milestone.project_id')
            ->join('client', 'client.id', '=', 'project.client_id')
            ->where('client.customer_id', $customer->id);
    }
}
