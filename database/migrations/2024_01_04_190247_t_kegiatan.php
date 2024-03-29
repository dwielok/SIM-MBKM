<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add periode_id in m_kegiatan_perusahaan reference periode_id
        Schema::table('m_kegiatan_perusahaan', function (Blueprint $table) {
            $table->text('periode_id')->after('jenis_magang_id');
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
        Schema::table('m_kegiatan_perusahaan', function (Blueprint $table) {
            $table->dropColumn('periode_id');
        });
    }
}
