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
        $this->call([
            //SDateSeeder::class,
            SGroupSeeder::class,
            SUserSeeder::class,
            SMenuSeeder::class,
            SGroupMenuSeeder::class,
            MJurusanSeeder::class,
            MProdiSeeder::class,
            MProgramSeeder::class,
            MKegiatanSeeder::class,
        ]);
    }
}
