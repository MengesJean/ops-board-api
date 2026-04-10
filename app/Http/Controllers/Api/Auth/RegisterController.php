<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterCustomerRequest;
use App\Http\Resources\CustomerResource;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

/**
 * @group Customer Authentication
 */
class RegisterController extends Controller
{
    /**
     * Register a new customer.
     *
     * Creates a new customer account and immediately starts a session for them
     * via the `customer` guard. Subsequent requests from the SPA will be
     * authenticated via the session cookie set on this response.
     *
     * @unauthenticated
     *
     * @response 201 scenario="Account created" {
     *   "data": {
     *     "id": 1,
     *     "name": "Ada Lovelace",
     *     "email": "ada@example.com",
     *     "email_verified_at": null,
     *     "created_at": "2026-04-10T12:00:00+00:00"
     *   }
     * }
     * @response 422 scenario="Validation failed" {
     *   "message": "The email has already been taken.",
     *   "errors": {"email": ["The email has already been taken."]}
     * }
     */
    public function __invoke(RegisterCustomerRequest $request): JsonResponse
    {
        $customer = Customer::create($request->validated());

        Auth::guard('customer')->login($customer);
        $request->session()->regenerate();

        return CustomerResource::make($customer)
            ->response()
            ->setStatusCode(201);
    }
}
