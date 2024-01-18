<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SGroupMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $menu_super = [];
        $menu_admin = [];
        for($i = 1; $i <= 11; $i++){
            $menu_super[] = ['group_id'  => 1, 'menu_id'   => $i, 'c'   => 1, 'r'    => 1, 'u'   => 1, 'd' => 1];
            $menu_admin[] = ['group_id'  => 2, 'menu_id'   => $i, 'c'   => 0, 'r'    => 1, 'u'   => 0, 'd' => 0];
        }

        DB::table('s_group_menu')->insert($menu_super);
        DB::table('s_group_menu')->insert($menu_admin);
    }
}
