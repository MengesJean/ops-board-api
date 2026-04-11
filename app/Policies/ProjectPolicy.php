<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class ProjectPolicy
{
    /**
     * Admin users (Filament backoffice) bypass ownership checks entirely.
     * Returning null falls through to the per-ability methods for other actors.
     *
     * Per-ability methods type-hint Customer, so any future Authenticatable
     * other than User|Customer must be rejected here to avoid a TypeError.
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

    public function viewAny(Customer $customer): bool
    {
        return true;
    }

    public function view(Customer $customer, Project $project): bool
    {
        return $this->ownsProject($customer, $project);
    }

    public function create(Customer $customer): bool
    {
        return true;
    }

    public function update(Customer $customer, Project $project): bool
    {
        return $this->ownsProject($customer, $project);
    }

    public function delete(Customer $customer, Project $project): bool
    {
        return $this->ownsProject($customer, $project);
    }

    private function ownsProject(Customer $customer, Project $project): bool
    {
        $project->loadMissing('client');

        return $project->client?->customer_id === $customer->id;
    }
}
