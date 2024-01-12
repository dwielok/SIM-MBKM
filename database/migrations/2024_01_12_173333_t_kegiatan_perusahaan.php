<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TKegiatanPerusahaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_kegiatan_perusahaan', function (Blueprint $table) {
            $table->text('prodi_id')->nullable()->after('jenis_magang_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //drop prodi_id from m_kegiatan_perusahaan
        Schema::table('m_kegiatan_perusahaan', function (Blueprint $table) {
            $table->dropColumn('prodi_id');
        });
    }
}
