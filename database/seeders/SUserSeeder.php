<?php

namespace Database\Seeders;

use App\Models\Setting\UserModel;
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
        UserModel::insert([
            [
                'user_id'   => 1, // Admin
                'group_id'  => 1, // Admin
                'username'  => 'admin',
                'name'      => 'Administrator',
                'email'     => 'admin@admin.com',
                'prodi_id'  => NULL,
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ], [
                'user_id'   => 2, // Koordinator TI
                'group_id'  => 2, // Koordinator TI
                'username'  => 'joni',
                'name'      => 'Joni',
                'email'     => 'koorti@admin.com',
                'prodi_id'  => 1,
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ], [
                'user_id'   => 3, // Koordinator SIB
                'group_id'  => 2, // Koordinator SIB
                'username'  => 'budi',
                'name'      => 'Budi',
                'email'     => 'koorsib@admin.com',
                'prodi_id'  => 2,
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ], [
                'user_id'   => 4, // Koordinator PPLS
                'group_id'  => 2, // Koordinator PPLS
                'username'  => 'andi',
                'name'      => 'Andi',
                'email'     => 'koorppls@admin.com',
                'prodi_id'  => 3,
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ], [
                'user_id'   => 5, //
                'group_id'  => 3, // Dosen
                'username'  => 'dosen',
                'name'      => 'Dosen',
                'email'     => 'dosen@admin.com',
                'prodi_id'  => NULL,
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ],
            [
                'user_id'   => 6, //
                'group_id'  => 4, // Mahasiswa
                'username'  => 'mahasiswa',
                'name'      => 'Mahasiswa',
                'email'     => 'mahasiswa@admin.com',
                'prodi_id'  => NULL,
                'password'  => password_hash('12345', PASSWORD_DEFAULT),
            ]
        ]);
    }
}
