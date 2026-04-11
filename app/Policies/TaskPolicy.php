<?php

namespace App\Policies;

use App\Models\Customer;
use App\Models\Task;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class TaskPolicy
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

    public function view(Customer $customer, Task $task): bool
    {
        return $this->ownsTask($customer, $task);
    }

    public function update(Customer $customer, Task $task): bool
    {
        return $this->ownsTask($customer, $task);
    }

    public function delete(Customer $customer, Task $task): bool
    {
        return $this->ownsTask($customer, $task);
    }

    private function ownsTask(Customer $customer, Task $task): bool
    {
        $task->loadMissing('project.client');

        return $task->project?->client?->customer_id === $customer->id;
    }
}
