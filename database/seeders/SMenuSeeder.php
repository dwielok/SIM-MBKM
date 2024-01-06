<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // for Super Admin and Admin
        DB::table('s_menu')->upsert([
            ['menu_id' => '1','menu_scope' => 'ALL','menu_code' => 'DASHBOARD','menu_name' => 'Dashboard','menu_url' => '/','menu_level' => '1','order_no' => '1','parent_id' => NULL,'class_tag' => 'dashboard','icon' => 'fas fa-tachometer-alt','is_active' => '1'],
            ['menu_id' => '2','menu_scope' => 'ADMIN','menu_code' => 'MASTER','menu_name' => 'Data Master','menu_url' => NULL,'menu_level' => '1','order_no' => '2','parent_id' => NULL,'class_tag' => 'master','icon' => 'fas fa-th','is_active' => '1'],
            ['menu_id' => '3','menu_scope' => 'ADMIN','menu_code' => 'TRANSACTION','menu_name' => 'Transaksi','menu_url' => NULL,'menu_level' => '1','order_no' => '3','parent_id' => NULL,'class_tag' => 'transaction','icon' => 'fas fa-edit','is_active' => '1'],
            ['menu_id' => '4','menu_scope' => 'ADMIN','menu_code' => 'REPORT','menu_name' => 'Laporan','menu_url' => NULL,'menu_level' => '1','order_no' => '4','parent_id' => NULL,'class_tag' => 'report','icon' => 'fas fa-file-invoice','is_active' => '1'],
            ['menu_id' => '5','menu_scope' => 'SUPER','menu_code' => 'SETTING','menu_name' => 'Setting','menu_url' => NULL,'menu_level' => '1','order_no' => '5','parent_id' => NULL,'class_tag' => 'setting','icon' => 'fas fa-cogs','is_active' => '1'],
            ['menu_id' => '6','menu_scope' => 'ADMIN','menu_code' => 'MASTER.JURUSAN','menu_name' => 'Jurusan','menu_url' => 'master/jurusan','menu_level' => '2','order_no' => '22','parent_id' => '2','class_tag' => 'master-jurusan','icon' => 'fas fa-minus text-xs','is_active' => '1'],
            ['menu_id' => '7','menu_scope' => 'ADMIN','menu_code' => 'MASTER.PRODI','menu_name' => 'Program Studi','menu_url' => 'master/prodi','menu_level' => '2','order_no' => '23','parent_id' => '2','class_tag' => 'master-prodi','icon' => 'fas fa-minus text-xs','is_active' => '1'],
        ], ['menu_id', 'menu_code'], ['menu_scope', 'menu_name', 'menu_url', 'class_tag']);
    }
}
