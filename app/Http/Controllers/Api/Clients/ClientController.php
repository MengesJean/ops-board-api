<?php

namespace App\Http\Controllers\Api\Clients;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\IndexClientRequest;
use App\Http\Requests\Client\StoreClientRequest;
use App\Http\Requests\Client\UpdateClientRequest;
use App\Http\Resources\Client\ClientResource;
use App\Models\Client;
use App\Models\Customer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

/**
 * @group Client Management
 *
 * APIs for managing clients owned by the authenticated customer.
 * Every endpoint is scoped to the caller's ownership; a customer can
 * only ever see and mutate their own clients.
 */
class ClientController extends Controller
{
    use AuthorizesRequests;

    /**
     * List clients.
     *
     * Returns a paginated list of the authenticated customer's clients.
     * Supports a text `search` across `name` and `company_name`, and a `status` filter.
     *
     * @response 200 scenario="Paginated list" {
     *   "data": [
     *     {
     *       "id": 1,
     *       "customer_id": 1,
     *       "name": "Grace Hopper",
     *       "company_name": "Hopper Industries",
     *       "email": "grace@hopper.test",
     *       "phone": "+1 555 0123",
     *       "status": "active",
     *       "notes": null,
     *       "created_at": "2026-04-11T09:00:00+00:00",
     *       "updated_at": "2026-04-11T09:00:00+00:00"
     *     }
     *   ],
     *   "links": {"first": "...", "last": "...", "prev": null, "next": null},
     *   "meta": {"current_page": 1, "per_page": 15, "total": 1}
     * }
     * @response 401 scenario="Guest" {"message": "Unauthenticated."}
     */
    public function index(IndexClientRequest $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', Client::class);

        /** @var Customer $customer */
        $customer = $request->user();

        $clients = $customer->clients()
            ->when($request->string('search')->toString(), function ($query, string $search): void {
                $needle = '%'.strtolower($search).'%';
                $query->where(function ($inner) use ($needle): void {
                    $inner->whereRaw('LOWER(name) LIKE ?', [$needle])
                        ->orWhereRaw('LOWER(company_name) LIKE ?', [$needle]);
                });
            })
            ->when($request->string('status')->toString(), fn ($query, string $status) => $query->where('status', $status))
            ->latest('id')
            ->paginate($request->integer('per_page', 15))
            ->withQueryString();

        return ClientResource::collection($clients);
    }

    /**
     * Create a client.
     *
     * Creates a new client owned by the authenticated customer. The `customer_id`
     * is injected server-side from the session and cannot be set via the payload.
     *
     * @response 201 scenario="Created" {
     *   "data": {
     *     "id": 2,
     *     "customer_id": 1,
     *     "name": "Grace Hopper",
     *     "company_name": "Hopper Industries",
     *     "email": "grace@hopper.test",
     *     "phone": "+1 555 0123",
     *     "status": "lead",
     *     "notes": null,
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-04-11T09:00:00+00:00"
     *   }
     * }
     * @response 422 scenario="Validation failed" {
     *   "message": "The email field is required.",
     *   "errors": {"email": ["The email field is required."]}
     * }
     */
    public function store(StoreClientRequest $request): JsonResponse
    {
        $this->authorize('create', Client::class);

        /** @var Customer $customer */
        $customer = $request->user();

        $client = $customer->clients()->create($request->validated());

        return ClientResource::make($client)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Show a client.
     *
     * Returns a single client owned by the authenticated customer.
     *
     * @response 200 scenario="OK" {
     *   "data": {
     *     "id": 1,
     *     "customer_id": 1,
     *     "name": "Grace Hopper",
     *     "company_name": "Hopper Industries",
     *     "email": "grace@hopper.test",
     *     "phone": "+1 555 0123",
     *     "status": "active",
     *     "notes": null,
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-04-11T09:00:00+00:00"
     *   }
     * }
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     * @response 404 scenario="Client not found" {"message": "No query results for model [App\\Models\\Client]."}
     */
    public function show(Client $client): ClientResource
    {
        $this->authorize('view', $client);

        return ClientResource::make($client);
    }

    /**
     * Update a client.
     *
     * Updates a client owned by the authenticated customer. All writable fields
     * must be provided (PUT semantics); partial updates are also accepted via PATCH.
     *
     * @response 200 scenario="Updated" {
     *   "data": {
     *     "id": 1,
     *     "customer_id": 1,
     *     "name": "Grace Hopper",
     *     "company_name": "Hopper Industries",
     *     "email": "grace@hopper.test",
     *     "phone": "+1 555 0123",
     *     "status": "active",
     *     "notes": "Upgraded to enterprise plan.",
     *     "created_at": "2026-04-11T09:00:00+00:00",
     *     "updated_at": "2026-04-11T10:00:00+00:00"
     *   }
     * }
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     */
    public function update(UpdateClientRequest $request, Client $client): ClientResource
    {
        $this->authorize('update', $client);

        $client->update($request->validated());

        return ClientResource::make($client);
    }

    /**
     * Delete a client.
     *
     * Permanently deletes a client owned by the authenticated customer.
     *
     * @response 204 scenario="Deleted"
     * @response 403 scenario="Not owned by caller" {"message": "This action is unauthorized."}
     */
    public function destroy(Client $client): Response
    {
        $this->authorize('delete', $client);

        $client->delete();

        return response()->noContent();
    }
}
