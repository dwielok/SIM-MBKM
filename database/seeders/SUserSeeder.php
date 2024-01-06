<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('s_user')->insert([
            [
                'user_id'   => 1, // Super Admin
                'group_id'  => 1, // Super Admin
                'username'  => 'superadmin',
                'name'      => 'Super Administrator',
                'email'     => 'super@admin.com',
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ],[
                'user_id'   => 2, // Admin
                'group_id'  => 2, // Admin
                'username'  => 'admin',
                'name'      => 'Administrator',
                'email'     => 'admin@admin.com',
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ],[
                'user_id'   => 3, //
                'group_id'  => 3, // Dosen
                'username'  => 'dosen',
                'name'      => 'Dosen',
                'email'     => 'dosen@admin.com',
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ],
            [
                'user_id'   => 4, //
                'group_id'  => 4, // Mahasiswa
                'username'  => 'mahasiswa',
                'name'      => 'Mahasiswa',
                'email'     => 'mahasiswa@admin.com',
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ]
        ]);
    }
}
