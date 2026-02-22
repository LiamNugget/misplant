<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Misplant Admin',
            'email' => 'admin@misplant.net',
        ]);

        $this->call(MisplantSeeder::class);
    }
}
