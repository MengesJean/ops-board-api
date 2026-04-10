<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\Request;

/**
 * @group Customer Authentication
 */
class MeController extends Controller
{
    /**
     * Get the authenticated customer.
     *
     * Returns the currently authenticated customer's profile. Useful for the
     * SPA to bootstrap its auth state on page load.
     *
     * @authenticated
     *
     * @response 200 scenario="Authenticated" {
     *   "data": {
     *     "id": 1,
     *     "name": "Ada Lovelace",
     *     "email": "ada@example.com",
     *     "email_verified_at": null,
     *     "created_at": "2026-04-10T12:00:00+00:00"
     *   }
     * }
     * @response 401 scenario="Unauthenticated" {
     *   "message": "Unauthenticated."
     * }
     */
    public function __invoke(Request $request): CustomerResource
    {
        return CustomerResource::make($request->user());
    }
}
