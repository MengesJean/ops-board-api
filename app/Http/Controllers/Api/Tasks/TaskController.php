<?php

namespace App\Http\Controllers\Api\Tasks;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Task\ReorderTasksRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Http\Resources\Task\TaskResource;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

/**
 * @group Project Tasks
 *
 * APIs for managing tasks inside a project. The full ownership chain
 * `Customer → Client → Project → Task` is enforced on every endpoint:
 * a customer can only ever see and mutate tasks whose parent project they
 * own through one of their clients. A task may optionally be linked to a
 * milestone — that milestone must always belong to the **same** project.
 *
 * Routes are nested under `/api/projects/{project}/tasks` so that Laravel's
 * scoped route binding rejects URLs whose task does not belong to the
 * project in the path with a 404, preventing existence leaks across
 * customers.
 */
class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * List tasks for a project.
     *
     * Returns the full ordered list of tasks for a project. Pagination is
     * intentionally omitted: a project board is small and consumers want the
     * whole list at once. Results are ordered by `position`. Supports filtering
     * by `status`, `priority`, `project_milestone_id`, and a free-text search
     * on `title`.
     *
     * @urlParam project integer required The project ID. Example: 1
     *
     * @queryParam status string Filter by status (`todo`, `in_progress`, `done`). Example: in_progress
     * @queryParam priority string Filter by priority (`low`, `medium`, `high`). Example: high
     * @queryParam project_milestone_id integer Filter by milestone. Use `0` (or omit) to skip. Example: 4
     * @queryParam search string Free-text search on the title. Example: deploy
     *
     * @response 200 scenario="OK" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "project_id": 1,
     *       "project_milestone_id": 2,
     *       "title": "Write the technical brief",
     *       "description": "Cover the API contract and the rollout plan.",
     *       "status": "in_progress",
     *       "priority": "high",
     *       "position": 1,
     *       "due_date": "2026-05-15",
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
    public function index(Request $request, Project $project): AnonymousResourceCollection
    {
        $this->authorize('view', $project);

        $request->validate([
            'status' => ['sometimes', 'nullable', 'in:'.implode(',', TaskStatus::values())],
            'priority' => ['sometimes', 'nullable', 'in:'.implode(',', TaskPriority::values())],
            'project_milestone_id' => ['sometimes', 'nullable', 'integer'],
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
        ]);

        $tasks = $project->tasks()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->when($request->filled('priority'), fn ($q) => $q->where('priority', $request->string('priority')))
            ->when($request->filled('project_milestone_id'), fn ($q) => $q->where('project_milestone_id', $request->integer('project_milestone_id')))
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', '%'.$request->string('search').'%'))
            ->get();

        return TaskResource::collection($tasks);
    }

    /**
     * Create a task.
     *
     * Adds a new task to a project owned by the authenticated customer. The
     * `position` is auto-assigned (max+1 within the project) and is not
     * settable from the payload. Setting `status` to `done` immediately stamps
     * `completed_at`. If `project_milestone_id` is provided, that milestone
     * must belong to the **same** project.
     *
     * @urlParam project integer required The project ID. Example: 1
     *
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": 3,
     *     "project_id": 1,
     *     "project_milestone_id": null,
     *     "title": "Deploy v1 to production",
     *     "description": null,
     *     "status": "todo",
     *     "priority": "medium",
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
    public function store(StoreTaskRequest $request, Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $task = $project->tasks()->create($request->validated());

        return TaskResource::make($task)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Show a task.
     *
     * Returns a single task. The route binding is scoped, so the URL
     * `/api/projects/{project}/tasks/{task}` returns 404 if the task does
     * not belong to the project in the path.
     *
     * @urlParam project integer required The project ID. Example: 1
     * @urlParam task integer required The task ID. Example: 2
     *
     * @response 200 scenario="OK" {
     *   "data": {
     *     "id": 2,
     *     "project_id": 1,
     *     "project_milestone_id": null,
     *     "title": "Validate hero copy",
     *     "description": null,
     *     "status": "todo",
     *     "priority": "low",
     *     "position": 2,
     *     "due_date": null,
     *     "completed_at": null,
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-04-11T09:00:00+00:00"
     *   }
     * }
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     * @response 404 scenario="Task not found in this project" {"message": "No query results for model [App\\Models\\Task]."}
     */
    public function show(Project $project, Task $task): TaskResource
    {
        $this->authorize('view', $task);

        return TaskResource::make($task);
    }

    /**
     * Update a task.
     *
     * Updates a task owned by the authenticated customer. Transitioning
     * `status` to `done` stamps `completed_at`; transitioning away from `done`
     * clears it. The model handles this automatically. Send
     * `project_milestone_id: null` to detach the task from its milestone.
     *
     * @urlParam project integer required The project ID. Example: 1
     * @urlParam task integer required The task ID. Example: 2
     *
     * @response 200 scenario="Updated" {
     *   "data": {
     *     "id": 2,
     *     "project_id": 1,
     *     "project_milestone_id": 4,
     *     "title": "Validate hero copy",
     *     "description": "Sign-off from copywriter and PM.",
     *     "status": "done",
     *     "priority": "low",
     *     "position": 2,
     *     "due_date": null,
     *     "completed_at": "2026-06-19T17:00:00+00:00",
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-06-19T17:00:00+00:00"
     *   }
     * }
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     */
    public function update(UpdateTaskRequest $request, Project $project, Task $task): TaskResource
    {
        $this->authorize('update', $task);

        $task->update($request->validated());

        return TaskResource::make($task->fresh());
    }

    /**
     * Delete a task.
     *
     * Permanently deletes a task from a project owned by the authenticated
     * customer. Existing positions are not compacted; the next reorder or
     * create call handles ordering correctly.
     *
     * @urlParam project integer required The project ID. Example: 1
     * @urlParam task integer required The task ID. Example: 2
     *
     * @response 204 scenario="Deleted"
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     */
    public function destroy(Project $project, Task $task): Response
    {
        $this->authorize('delete', $task);

        $task->delete();

        return response()->noContent();
    }

    /**
     * Reorder tasks.
     *
     * Sets the order of tasks in a project. The provided list must contain
     * **exactly** the IDs of the project's current tasks (no missing, no
     * extras), in the desired final order. Positions are reassigned `1..N`
     * inside a transaction.
     *
     * @urlParam project integer required The project ID. Example: 1
     *
     * @response 200 scenario="Reordered" {
     *   "data": [
     *     {"id": 4, "project_id": 1, "project_milestone_id": null, "title": "Brief", "description": null, "status": "todo", "priority": "medium", "position": 1, "due_date": null, "completed_at": null, "created_at": "2026-04-11T09:00:00+00:00", "updated_at": "2026-04-11T09:00:00+00:00"}
     *   ]
     * }
     * @response 403 scenario="Project not owned by caller" {"message": "This action is unauthorized."}
     * @response 422 scenario="Incomplete list" {
     *   "message": "The task_ids list must contain exactly 4 entries (one per existing task).",
     *   "errors": {"task_ids": ["The task_ids list must contain exactly 4 entries (one per existing task)."]}
     * }
     */
    public function reorder(ReorderTasksRequest $request, Project $project): AnonymousResourceCollection
    {
        $this->authorize('view', $project);

        DB::transaction(function () use ($request, $project): void {
            foreach ($request->validated('task_ids') as $index => $id) {
                $project->tasks()
                    ->whereKey($id)
                    ->update(['position' => $index + 1]);
            }
        });

        return TaskResource::collection($project->tasks()->get());
    }
}
