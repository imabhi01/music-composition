<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\ZoodiacSignSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(ZoodiacSignSeeder::class);
    }
}
