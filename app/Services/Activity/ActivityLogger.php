<?php

namespace App\Services\Activity;

use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectMilestone;
use App\Models\Task;
use Closure;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class ActivityLogger
{
    private static bool $enabled = true;

    /**
     * Permanently disable the logger for the current process. Useful for
     * seeders, factory bootstraps, and tests that don't care about activity.
     */
    public static function disable(): void
    {
        self::$enabled = false;
    }

    public static function enable(): void
    {
        self::$enabled = true;
    }

    public static function isEnabled(): bool
    {
        return self::$enabled;
    }

    /**
     * Run the given callback with logging temporarily disabled. Used by
     * seeders / large bulk imports that should not pollute the timeline.
     *
     * @template T
     *
     * @param  Closure(): T  $callback
     * @return T
     */
    public static function withoutLogging(Closure $callback): mixed
    {
        $previous = self::$enabled;
        self::$enabled = false;

        try {
            return $callback();
        } finally {
            self::$enabled = $previous;
        }
    }

    /**
     * Record an activity row for the given subject.
     *
     * The customer_id and project_id are derived automatically from the
     * subject's relations; the actor is read from the active auth guards.
     * Failures are swallowed silently — the activity log must never break a
     * business write (e.g. during a customer cascade delete).
     *
     * @param  array<string, mixed>  $properties
     */
    public static function record(Model $subject, string $event, array $properties = []): ?ActivityLog
    {
        if (! self::$enabled) {
            return null;
        }

        try {
            $customerId = self::resolveCustomerId($subject);

            if ($customerId === null) {
                return null;
            }

            $projectId = self::resolveProjectId($subject);
            $actor = self::resolveActor();

            // Snapshot the subject's display label so the timeline stays
            // readable even after the subject row has been deleted.
            $properties = array_merge(
                ['label' => self::resolveLabel($subject)],
                $properties,
            );

            return ActivityLog::create([
                'customer_id' => $customerId,
                'project_id' => $projectId,
                'actor_type' => $actor?->getMorphClass(),
                'actor_id' => $actor?->getKey(),
                'subject_type' => $subject->getMorphClass(),
                'subject_id' => $subject->getKey(),
                'event' => $event,
                'properties' => $properties,
                'created_at' => now(),
            ]);
        } catch (Throwable) {
            return null;
        }
    }

    private static function resolveCustomerId(Model $subject): ?int
    {
        return match (true) {
            $subject instanceof Project => $subject->client?->customer_id
                ?? $subject->loadMissing('client')->client?->customer_id,
            $subject instanceof ProjectMilestone, $subject instanceof Task => $subject->project?->client?->customer_id
                ?? $subject->loadMissing('project.client')->project?->client?->customer_id,
            $subject instanceof Client => $subject->customer_id,
            default => null,
        };
    }

    private static function resolveProjectId(Model $subject): ?int
    {
        return match (true) {
            $subject instanceof Project => $subject->id,
            $subject instanceof ProjectMilestone, $subject instanceof Task => $subject->project_id,
            default => null,
        };
    }

    private static function resolveActor(): ?Model
    {
        $customer = auth('sanctum')->user();

        if ($customer instanceof Customer) {
            return $customer;
        }

        $web = auth('web')->user();

        if ($web !== null) {
            return $web;
        }

        return null;
    }

    private static function resolveLabel(Model $subject): ?string
    {
        return match (true) {
            $subject instanceof Project, $subject instanceof Client => $subject->name,
            $subject instanceof ProjectMilestone, $subject instanceof Task => $subject->title,
            default => null,
        };
    }
}
