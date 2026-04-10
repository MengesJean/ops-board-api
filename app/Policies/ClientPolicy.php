<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;

class ClientPolicy
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

    public function view(Customer $customer, Client $client): bool
    {
        return $client->customer_id === $customer->id;
    }

    public function create(Customer $customer): bool
    {
        return true;
    }

    public function update(Customer $customer, Client $client): bool
    {
        return $client->customer_id === $customer->id;
    }

    public function delete(Customer $customer, Client $client): bool
    {
        return $client->customer_id === $customer->id;
    }
}
