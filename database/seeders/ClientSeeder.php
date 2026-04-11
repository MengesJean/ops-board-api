<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Customer;
use Illuminate\Database\Seeder;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        Customer::all()->each(function (Customer $customer): void {
            Client::factory()
                ->count(random_int(2, 5))
                ->forCustomer($customer)
                ->create();
        });
    }
}
