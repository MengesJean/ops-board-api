<?php

namespace App\Http\Controllers\Api\ProjectMilestones;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProjectMilestone\ReorderProjectMilestonesRequest;
use App\Http\Requests\ProjectMilestone\StoreProjectMilestoneRequest;
use App\Http\Requests\ProjectMilestone\UpdateProjectMilestoneRequest;
use App\Http\Resources\ProjectMilestone\ProjectMilestoneResource;
use App\Models\Project;
use App\Models\ProjectMilestone;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * @group Project Milestones
 *
 * APIs for managing milestones inside a project. The full ownership chain
 * `Customer → Client → Project → ProjectMilestone` is enforced on every
 * endpoint: a customer can only ever see and mutate milestones whose parent
 * project they own through one of their clients.
 *
 * Routes are nested under `/api/projects/{project}/milestones` so that
 * Laravel's scoped route binding rejects URLs whose milestone does not belong
 * to the project in the path with a 404, preventing existence leaks across
 * customers.
 */
class ProjectMilestoneController extends Controller
{
    use AuthorizesRequests;

    /**
     * List milestones for a project.
     *
     * Returns the full ordered roadmap of a project. Pagination is intentionally
     * omitted: roadmaps are small (typically 5–30 items) and consumers want the
     * whole list at once. Results are ordered by `position`.
     *
     * @urlParam project integer required The project ID. Example: 1
     *
     * @response 200 scenario="OK" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "project_id": 1,
     *       "title": "Discovery",
     *       "description": "Stakeholder interviews and scoping.",
     *       "status": "done",
     *       "position": 1,
     *       "due_date": "2026-05-15",
     *       "completed_at": "2026-05-14T16:30:00+00:00",
     *       "created_at": "2026-04-11T09:00:00+00:00",
     *       "updated_at": "2026-05-14T16:30:00+00:00"
     *     },
     *     {
     *       "id": 2,
     *       "project_id": 1,
     *       "title": "Design ready",
     *       "description": null,
     *       "status": "in_progress",
     *       "position": 2,
     *       "due_date": "2026-06-20",
     *       "completed_at": null,
     *       "created_at": "2026-04-11T09:00:00+00:00",
     *       "updated_at": "2026-04-11T09:00:00+00:00"
     *     }
     *   ]
     * }
     * @response 401 scenario="Guest" {"message": "Unauthenticated."}
     * @response 403 scenario="Project not owned by caller" {"message": "This action is unauthorized."}
     * @response 404 scenario="Project not found" {"message": "No query results for model [App\\Models\\Project]."}
     */
    public function index(Project $project): AnonymousResourceCollection
    {
        $this->authorize('view', $project);

        return ProjectMilestoneResource::collection($project->milestones()->get());
    }

    /**
     * Create a milestone.
     *
     * Adds a new milestone to a project owned by the authenticated customer.
     * The `position` is auto-assigned (max+1 within the project) and is not
     * settable from the payload. Setting `status` to `done` immediately stamps
     * `completed_at`.
     *
     * @urlParam project integer required The project ID. Example: 1
     *
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": 3,
     *     "project_id": 1,
     *     "title": "Client UAT",
     *     "description": null,
     *     "status": "pending",
     *     "position": 3,
     *     "due_date": "2026-08-01",
     *     "completed_at": null,
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-04-11T09:00:00+00:00"
     *   }
     * }
     * @response 403 scenario="Project not owned by caller" {"message": "This action is unauthorized."}
     * @response 422 scenario="Validation failed" {
     *   "message": "The title field is required.",
     *   "errors": {"title": ["The title field is required."]}
     * }
     */
    public function store(StoreProjectMilestoneRequest $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $milestone = $project->milestones()->create($request->validated());

        return ProjectMilestoneResource::make($milestone)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Show a milestone.
     *
     * Returns a single milestone. The route binding is scoped, so the URL
     * `/api/projects/{project}/milestones/{milestone}` returns 404 if the
     * milestone does not belong to the project in the path.
     *
     * @urlParam project integer required The project ID. Example: 1
     * @urlParam milestone integer required The milestone ID. Example: 2
     *
     * @response 200 scenario="OK" {
     *   "data": {
     *     "id": 2,
     *     "project_id": 1,
     *     "title": "Design ready",
     *     "description": null,
     *     "status": "in_progress",
     *     "position": 2,
     *     "due_date": "2026-06-20",
     *     "completed_at": null,
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-04-11T09:00:00+00:00"
     *   }
     * }
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     * @response 404 scenario="Milestone not found in this project" {"message": "No query results for model [App\\Models\\ProjectMilestone]."}
     */
    public function show(Project $project, ProjectMilestone $milestone): ProjectMilestoneResource
    {
        $this->authorize('view', $milestone);

        return ProjectMilestoneResource::make($milestone);
    }

    /**
     * Update a milestone.
     *
     * Updates a milestone owned by the authenticated customer. Transitioning
     * `status` to `done` stamps `completed_at`; transitioning away from `done`
     * clears it. The model handles this automatically.
     *
     * @urlParam project integer required The project ID. Example: 1
     * @urlParam milestone integer required The milestone ID. Example: 2
     *
     * @response 200 scenario="Updated" {
     *   "data": {
     *     "id": 2,
     *     "project_id": 1,
     *     "title": "Design ready",
     *     "description": "Sign-off from all stakeholders.",
     *     "status": "done",
     *     "position": 2,
     *     "due_date": "2026-06-20",
     *     "completed_at": "2026-06-19T17:00:00+00:00",
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-06-19T17:00:00+00:00"
     *   }
     * }
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     */
    public function update(UpdateProjectMilestoneRequest $request, Project $project, ProjectMilestone $milestone): ProjectMilestoneResource
    {
        $this->authorize('update', $milestone);

        $milestone->update($request->validated());

        return ProjectMilestoneResource::make($milestone->fresh());
    }

    /**
     * Delete a milestone.
     *
     * Permanently deletes a milestone from a project owned by the authenticated
     * customer. Existing positions are not compacted; the next reorder or
     * create call handles ordering correctly.
     *
     * @urlParam project integer required The project ID. Example: 1
     * @urlParam milestone integer required The milestone ID. Example: 2
     *
     * @response 204 scenario="Deleted"
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     */
    public function destroy(Project $project, ProjectMilestone $milestone): Response
    {
        $this->authorize('delete', $milestone);

        $milestone->delete();

        return response()->noContent();
    }

    /**
     * Reorder milestones.
     *
     * Sets the order of milestones in a project. The provided list must contain
     * **exactly** the IDs of the project's current milestones (no missing, no
     * extras), in the desired final order. Positions are reassigned `1..N`
     * inside a transaction.
     *
     * @urlParam project integer required The project ID. Example: 1
     *
     * @response 200 scenario="Reordered" {
     *   "data": [
     *     {"id": 4, "position": 1, "title": "Discovery", "status": "done", "project_id": 1, "description": null, "due_date": null, "completed_at": "2026-05-14T16:30:00+00:00", "created_at": "2026-04-11T09:00:00+00:00", "updated_at": "2026-05-14T16:30:00+00:00"},
     *     {"id": 1, "position": 2, "title": "Design ready", "status": "in_progress", "project_id": 1, "description": null, "due_date": null, "completed_at": null, "created_at": "2026-04-11T09:00:00+00:00", "updated_at": "2026-04-11T09:00:00+00:00"}
     *   ]
     * }
     * @response 403 scenario="Project not owned by caller" {"message": "This action is unauthorized."}
     * @response 422 scenario="Incomplete list" {
     *   "message": "The milestone_ids list must contain exactly 4 entries (one per existing milestone).",
     *   "errors": {"milestone_ids": ["The milestone_ids list must contain exactly 4 entries (one per existing milestone)."]}
     * }
     */
    public function reorder(ReorderProjectMilestonesRequest $request, Project $project): AnonymousResourceCollection
    {
        $this->authorize('view', $project);

        DB::transaction(function () use ($request, $project): void {
            foreach ($request->validated('milestone_ids') as $index => $id) {
                $project->milestones()
                    ->whereKey($id)
                    ->update(['position' => $index + 1]);
            }
        });

        return ProjectMilestoneResource::collection($project->milestones()->get());
    }
}
