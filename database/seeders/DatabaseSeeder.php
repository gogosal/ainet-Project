<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            PriceSeeder::class,
            ColorSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            TshirtImageSeeder::class,
        ]);
    }
}
