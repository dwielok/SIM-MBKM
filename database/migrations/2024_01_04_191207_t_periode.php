<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TPeriode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add periode_id in m_kegiatan_perusahaan reference periode_id
        Schema::table('m_periode', function (Blueprint $table) {
            $table->integer('is_active')->after('tahun_ajar')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //remove periode_id in m_kegiatan_perusahaan
        Schema::table('m_periode', function (Blueprint $table) {
            $table->dropColumn('is_active');
        });
    }
}
