<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Services\ProjectProgressService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;

/**
 * @group Project Progress
 *
 * Read-only progression endpoint for a project. Computes a snapshot from the
 * project's tasks (source of truth) and milestones, and returns it alongside a
 * per-milestone breakdown so the front-end can render a roadmap with visual
 * progress without firing additional requests.
 *
 * The full ownership chain `Customer → Client → Project` is enforced via the
 * existing `ProjectPolicy`.
 */
class ProjectProgressController extends Controller
{
    use AuthorizesRequests;

    /**
     * Get the project's progression.
     *
     * Returns global task/milestone counts, the next-due task and milestone,
     * an `is_overdue` flag, and a milestone-by-milestone task breakdown.
     *
     * @urlParam project integer required The project ID. Example: 1
     *
     * @response 200 scenario="OK" {
     *   "data": {
     *     "project": {
     *       "total_tasks": 12,
     *       "todo_tasks": 4,
     *       "in_progress_tasks": 3,
     *       "completed_tasks": 5,
     *       "overdue_tasks": 1,
     *       "completion_rate": 0.4167,
     *       "has_tasks": true,
     *       "total_milestones": 4,
     *       "completed_milestones": 1,
     *       "next_due_task": {"id": 7, "title": "Validate hero copy", "due_date": "2026-05-15"},
     *       "next_due_milestone": {"id": 2, "title": "Design ready", "due_date": "2026-06-20"},
     *       "is_overdue": false
     *     },
     *     "milestones": [
     *       {
     *         "id": 1,
     *         "title": "Discovery",
     *         "status": "done",
     *         "position": 1,
     *         "due_date": "2026-05-15",
     *         "completed_at": "2026-05-14T16:30:00+00:00",
     *         "total_tasks": 4,
     *         "completed_tasks": 4,
     *         "completion_rate": 1
     *       },
     *       {
     *         "id": 2,
     *         "title": "Design ready",
     *         "status": "in_progress",
     *         "position": 2,
     *         "due_date": "2026-06-20",
     *         "completed_at": null,
     *         "total_tasks": 6,
     *         "completed_tasks": 1,
     *         "completion_rate": 0.1667
     *       }
     *     ]
     *   }
     * }
     * @response 401 scenario="Guest" {"message": "Unauthenticated."}
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     * @response 404 scenario="Project not found" {"message": "No query results for model [App\\Models\\Project]."}
     */
    public function __invoke(Project $project, ProjectProgressService $service): JsonResponse
    {
        $this->authorize('view', $project);

        return response()->json([
            'data' => $service->forProjectWithMilestones($project),
        ]);
    }
}
