<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

/**
 * @group Customer Authentication
 */
class LogoutController extends Controller
{
    /**
     * Log the current customer out.
     *
     * Terminates the session on the server side, invalidates the session
     * cookie and rotates the CSRF token. Call this from the SPA when the
     * user signs out.
     *
     * @authenticated
     *
     * @response 204 scenario="Logged out"
     */
    public function __invoke(Request $request): Response
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->noContent();
    }
}
