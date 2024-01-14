<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TKegiatanPerusahaan2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_kegiatan_perusahaan', function (Blueprint $table) {
            $table->date('batas_pendaftaran')->nullable()->after('akhir_kegiatan');
            $table->text('contact_person')->nullable()->after('batas_pendaftaran');
            $table->text('kualifikasi')->nullable()->after('contact_person');
            $table->text('fasilitas')->nullable()->after('kualifikasi');
            $table->string('flyer')->nullable()->after('fasilitas');
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
            $table->dropColumn('batas_pendaftaran');
            $table->dropColumn('contact_person');
            $table->dropColumn('kualifikasi');
            $table->dropColumn('fasilitas');
            $table->dropColumn('flyer');
        });
    }
}
