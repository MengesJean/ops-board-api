<?php

namespace App\Http\Controllers\Api\Projects;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\IndexProjectRequest;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Http\Resources\Project\ProjectResource;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Project Management
 *
 * APIs for managing projects belonging to clients owned by the authenticated
 * customer. The full ownership chain is `Customer → Client → Project`, and
 * every endpoint enforces it: a customer can only ever see and mutate
 * projects whose parent client they own.
 */
class ProjectController extends Controller
{
    use AuthorizesRequests;

    /**
     * List projects.
     *
     * Returns a paginated list of projects across all clients owned by the
     * authenticated customer. Supports text search on `name` and `reference`,
     * filters on `client_id`, `status`, `priority`, `health`, and sorting on
     * `due_date`, `updated_at`, or `created_at`.
     *
     * @response 200 scenario="Paginated list" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "client_id": 1,
     *       "name": "Acme website redesign",
     *       "reference": "PRJ-2026-001",
     *       "description": "Full marketing site redesign.",
     *       "status": "active",
     *       "priority": "high",
     *       "health": "good",
     *       "start_date": "2026-05-01",
     *       "due_date": "2026-09-30",
     *       "budget": "25000.00",
     *       "notes": null,
     *       "client": {"id": 1, "name": "Grace Hopper", "company_name": "Hopper Industries"},
     *       "created_at": "2026-04-11T09:00:00+00:00",
     *       "updated_at": "2026-04-11T09:00:00+00:00"
     *     }
     *   ],
     *   "links": {"first": "...", "last": "...", "prev": null, "next": null},
     *   "meta": {"current_page": 1, "per_page": 15, "total": 1}
     * }
     * @response 401 scenario="Guest" {"message": "Unauthenticated."}
     * @response 422 scenario="Invalid filters" {
     *   "message": "The selected status is invalid.",
     *   "errors": {"status": ["The selected status is invalid."]}
     * }
     */
    public function index(IndexProjectRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Project::class);

        /** @var Customer $customer */
        $customer = $request->user();

        $sort = $request->string('sort')->toString() ?: 'id';
        $direction = $request->string('direction')->toString() ?: 'desc';

        // Column references must be prefixed with `project.` because
        // hasManyThrough joins both `project` and `client`, which share
        // overlapping column names (name, status, created_at, ...).
        $projects = $customer->projects()
            ->with('client')
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $needle = '%'.strtolower($search).'%';
                $query->where(function ($inner) use ($needle): void {
                    $inner->whereRaw('LOWER(project.name) LIKE ?', [$needle])
                        ->orWhereRaw('LOWER(project.reference) LIKE ?', [$needle]);
                });
            })
            ->when($request->integer('client_id'), fn ($query, int $id) => $query->where('project.client_id', $id))
            ->when($request->string('status')->toString(), fn ($query, string $status) => $query->where('project.status', $status))
            ->when($request->string('priority')->toString(), fn ($query, string $priority) => $query->where('project.priority', $priority))
            ->when($request->string('health')->toString(), fn ($query, string $health) => $query->where('project.health', $health))
            ->orderBy('project.'.$sort, $direction)
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return ProjectResource::collection($projects);
    }

    /**
     * Create a project.
     *
     * Creates a new project under one of the authenticated customer's clients.
     * The `client_id` must reference a client owned by the caller; otherwise
     * validation fails with 422.
     *
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": 2,
     *     "client_id": 1,
     *     "name": "Acme website redesign",
     *     "reference": "PRJ-2026-001",
     *     "description": "Full marketing site redesign.",
     *     "status": "planned",
     *     "priority": "high",
     *     "health": "good",
     *     "start_date": "2026-05-01",
     *     "due_date": "2026-09-30",
     *     "budget": "25000.00",
     *     "notes": null,
     *     "client": {"id": 1, "name": "Grace Hopper", "company_name": "Hopper Industries"},
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-04-11T09:00:00+00:00"
     *   }
     * }
     * @response 422 scenario="Validation failed" {
     *   "message": "The name field is required.",
     *   "errors": {"name": ["The name field is required."]}
     * }
     */
    public function store(StoreProjectRequest $request): JsonResponse
    {
        $this->authorize('create', Project::class);

        $project = Project::create($request->validated())->load('client');

        return ProjectResource::make($project)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Show a project.
     *
     * Returns a single project belonging to one of the authenticated
     * customer's clients, including a minimal client summary.
     *
     * @response 200 scenario="OK" {
     *   "data": {
     *     "id": 1,
     *     "client_id": 1,
     *     "name": "Acme website redesign",
     *     "reference": "PRJ-2026-001",
     *     "description": "Full marketing site redesign.",
     *     "status": "active",
     *     "priority": "high",
     *     "health": "good",
     *     "start_date": "2026-05-01",
     *     "due_date": "2026-09-30",
     *     "budget": "25000.00",
     *     "notes": null,
     *     "client": {"id": 1, "name": "Grace Hopper", "company_name": "Hopper Industries"},
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-04-11T09:00:00+00:00"
     *   }
     * }
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     * @response 404 scenario="Project not found" {"message": "No query results for model [App\\Models\\Project]."}
     */
    public function show(Project $project): ProjectResource
    {
        $this->authorize('view', $project);

        return ProjectResource::make($project->load('client'));
    }

    /**
     * Update a project.
     *
     * Updates a project belonging to one of the authenticated customer's
     * clients. Reassigning to a `client_id` owned by another customer is
     * blocked at validation time (422), not at the policy layer.
     *
     * @response 200 scenario="Updated" {
     *   "data": {
     *     "id": 1,
     *     "client_id": 1,
     *     "name": "Acme website redesign",
     *     "reference": "PRJ-2026-001",
     *     "description": "Full marketing site redesign.",
     *     "status": "active",
     *     "priority": "high",
     *     "health": "warning",
     *     "start_date": "2026-05-01",
     *     "due_date": "2026-10-15",
     *     "budget": "30000.00",
     *     "notes": "Pushed go-live by two weeks.",
     *     "client": {"id": 1, "name": "Grace Hopper", "company_name": "Hopper Industries"},
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-04-11T10:00:00+00:00"
     *   }
     * }
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     * @response 422 scenario="Foreign client_id" {
     *   "message": "The selected client id is invalid.",
     *   "errors": {"client_id": ["The selected client id is invalid."]}
     * }
     */
    public function update(UpdateProjectRequest $request, Project $project): ProjectResource
    {
        $this->authorize('update', $project);

        $project->update($request->validated());

        return ProjectResource::make($project->load('client'));
    }

    /**
     * Delete a project.
     *
     * Permanently deletes a project belonging to one of the authenticated
     * customer's clients.
     *
     * @response 204 scenario="Deleted"
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     */
    public function destroy(Project $project): Response
    {
        $this->authorize('delete', $project);

        $project->delete();

        return response()->noContent();
    }
}
