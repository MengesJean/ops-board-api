<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\ProjectMilestone;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class ProjectMilestonePolicy
{
    /**
     * Admin users (Filament backoffice) bypass ownership checks entirely.
     * Returning null falls through to the per-ability methods for other actors.
     */
    public function before(Authenticatable $actor, string $ability): ?bool
    {
        if ($actor instanceof User) {
            return true;
        }

        if (! $actor instanceof Customer) {
            return false;
        }

        return null;
    }

    public function view(Customer $customer, ProjectMilestone $milestone): bool
    {
        return $this->ownsMilestone($customer, $milestone);
    }

    public function update(Customer $customer, ProjectMilestone $milestone): bool
    {
        return $this->ownsMilestone($customer, $milestone);
    }

    public function delete(Customer $customer, ProjectMilestone $milestone): bool
    {
        return $this->ownsMilestone($customer, $milestone);
    }

    private function ownsMilestone(Customer $customer, ProjectMilestone $milestone): bool
    {
        $milestone->loadMissing('project.client');

        return $milestone->project?->client?->customer_id === $customer->id;
    }
}
