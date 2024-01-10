<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TTipeKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add periode_id and prodi_id to m_tipe_kegiatan
        Schema::table('m_tipe_kegiatan', function (Blueprint $table) {
            $table->unsignedBigInteger('prodi_id')->index()->nullable()->after('nama_kegiatan');
            $table->unsignedBigInteger('periode_id')->index()->nullable()->after('prodi_id');
            $table->foreign('periode_id')->references('periode_id')->on('m_periode');
            $table->foreign('prodi_id')->references('prodi_id')->on('m_prodi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop periode_id and prodi_id from m_tipe_kegiatan
        Schema::table('m_tipe_kegiatan', function (Blueprint $table) {
            $table->dropForeign('m_tipe_kegiatan_periode_id_foreign');
            $table->dropForeign('m_tipe_kegiatan_prodi_id_foreign');
            $table->dropColumn('periode_id');
            $table->dropColumn('prodi_id');
        });
    }
}
