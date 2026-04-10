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
     */
    public function before(Authenticatable $actor, string $ability): ?bool
    {
        return $actor instanceof User ? true : null;
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
