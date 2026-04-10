<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginCustomerRequest;
use App\Http\Resources\CustomerResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

/**
 * @group Customer Authentication
 */
class LoginController extends Controller
{
    /**
     * Log a customer in.
     *
     * Authenticates a customer against the `customer` guard using their
     * email + password. On success, a session cookie is set and subsequent
     * requests to protected endpoints will be authenticated via `auth:sanctum`.
     *
     * @unauthenticated
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
     * @response 422 scenario="Bad credentials" {
     *   "message": "These credentials do not match our records.",
     *   "errors": {"email": ["These credentials do not match our records."]}
     * }
     */
    public function __invoke(LoginCustomerRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (! Auth::guard('customer')->attempt($credentials, remember: true)) {
            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $request->session()->regenerate();

        return CustomerResource::make(Auth::guard('customer')->user())
            ->response();
    }
}
