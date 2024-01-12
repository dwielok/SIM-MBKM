<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TPeriode2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_periode', function (Blueprint $table) {
            $table->integer('is_current')->after('is_active')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop is_current from m_periode
        Schema::table('m_periode', function (Blueprint $table) {
            $table->dropColumn('is_current');
        });
    }
}
