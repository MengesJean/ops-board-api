<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Resources\Activity\ActivityLogResource;
use App\Models\ActivityLog;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

/**
 * @group Project Activity
 *
 * Read-only timeline of business events that happened on a project. Events
 * are recorded by model observers (Project / ProjectMilestone / Task) and
 * stored in the singular `activity_log` table, scoped to the owning customer
 * via a denormalised `customer_id`.
 *
 * The full ownership chain `Customer → Client → Project` is enforced via the
 * existing `ProjectPolicy`.
 */
class ProjectActivityController extends Controller
{
    use AuthorizesRequests;

    /**
     * List the activity for a project.
     *
     * Returns a paginated, descending-by-date timeline of every recorded
     * business event tied to the project (status changes, completions,
     * milestone attach/detach, deletions…). The page size defaults to 20 and
     * can be tuned via the `per_page` query parameter (max 100).
     *
     * @urlParam project integer required The project ID. Example: 1
     *
     * @queryParam per_page integer Items per page (max 100). Example: 20
     *
     * @response 200 scenario="OK" {
     *   "data": [
     *     {
     *       "id": 42,
     *       "event": "task.completed",
     *       "subject": {"type": "task", "id": 7, "label": "Validate hero copy"},
     *       "project_id": 1,
     *       "actor": {"type": "customer", "id": 3, "name": "Grace Hopper"},
     *       "properties": {"label": "Validate hero copy"},
     *       "created_at": "2026-06-19T17:00:00+00:00"
     *     },
     *     {
     *       "id": 41,
     *       "event": "task.status_changed",
     *       "subject": {"type": "task", "id": 7, "label": "Validate hero copy"},
     *       "project_id": 1,
     *       "actor": {"type": "customer", "id": 3, "name": "Grace Hopper"},
     *       "properties": {"label": "Validate hero copy", "from": "in_progress", "to": "done"},
     *       "created_at": "2026-06-19T17:00:00+00:00"
     *     }
     *   ],
     *   "links": {"first": "...", "last": "...", "prev": null, "next": null},
     *   "meta": {"current_page": 1, "per_page": 20, "total": 42}
     * }
     * @response 401 scenario="Guest" {"message": "Unauthenticated."}
     * @response 403 scenario="Project not owned by caller" {"message": "This action is unauthorized."}
     * @response 404 scenario="Project not found" {"message": "No query results for model [App\\Models\\Project]."}
     */
    public function __invoke(Request $request, Project $project): AnonymousResourceCollection
    {
        $this->authorize('view', $project);

        $perPage = min(max((int) $request->integer('per_page', 20), 1), 100);

        $activities = ActivityLog::query()
            ->forProject($project->id)
            ->with(['subject', 'actor'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return ActivityLogResource::collection($activities);
    }
}
