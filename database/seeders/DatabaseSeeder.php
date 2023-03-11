<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        // \App\Models\Line::factory(18)->create();
        // \App\Models\Example::factory(28)->create();
        $this->call([
            PosSeeder::class,
            // WordSeeder::class,
        ]);
    }
}
