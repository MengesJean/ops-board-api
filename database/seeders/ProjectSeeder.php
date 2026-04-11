<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Project;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    public function run(): void
    {
        Client::all()->each(function (Client $client): void {
            Project::factory()
                ->count(random_int(1, 4))
                ->forClient($client)
                ->create();
        });
    }
}
