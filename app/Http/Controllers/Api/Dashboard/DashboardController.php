<?php

namespace App\Http\Controllers\Api\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * @group Dashboard
 *
 * Aggregated read model powering the OpsBoard front-end dashboard. The
 * payload is built in `DashboardService` and bundles project & task stats,
 * priority lists (overdue tasks, upcoming milestones, at-risk projects), a
 * synthetic projects view, and the customer's recent activity feed — in a
 * single response so the front does not need to fan out multiple calls.
 *
 * Ownership is enforced inside the service: every read is scoped to the
 * authenticated customer's id, so no policy is needed at the route level.
 */
class DashboardController extends Controller
{
    /**
     * Get the dashboard payload.
     *
     * Returns a stable, structured object containing the four sections the
     * front needs to render the dashboard page in one shot:
     * `stats`, `priorities`, `projects`, and `recent_activity`.
     *
     * @response 200 scenario="OK" {
     *   "data": {
     *     "stats": {
     *       "active_projects_count": 3,
     *       "completed_projects_count": 1,
     *       "warning_projects_count": 1,
     *       "critical_projects_count": 0,
     *       "overdue_tasks_count": 2,
     *       "due_today_tasks_count": 1,
     *       "upcoming_milestones_count": 1,
     *       "global_completion_rate": 0.42
     *     },
     *     "priorities": {
     *       "overdue_tasks": [],
     *       "due_today_tasks": [],
     *       "upcoming_milestones": [],
     *       "at_risk_projects": []
     *     },
     *     "projects": [],
     *     "recent_activity": []
     *   }
     * }
     * @response 401 scenario="Guest" {"message": "Unauthenticated."}
     */
    public function __invoke(Request $request, DashboardService $service): JsonResponse
    {
        /** @var Customer $customer */
        $customer = $request->user();

        return response()->json([
            'data' => $service->buildFor($customer),
        ]);
    }
}
