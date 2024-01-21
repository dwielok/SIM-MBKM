<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MPendaftaran extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_pendaftaran', function (Blueprint $table) {
            $table->id('pendaftaran_id');
            $table->unsignedBigInteger('mahasiswa_id')->index();
            $table->unsignedBigInteger('kegiatan_perusahaan_id')->index();
            $table->unsignedBigInteger('periode_id')->index();
            $table->string('kode_pendaftaran', 10);
            $table->integer('tipe_pendaftar')->comment('0: Ketua, 1: Anggota, 2: Individu');
            $table->integer('status')->comment('0: Menunggu, 1: Diterima, 2: Ditolak');

            $table->foreign('mahasiswa_id')->references('mahasiswa_id')->on('m_mahasiswa');
            $table->foreign('kegiatan_perusahaan_id')->references('kegiatan_perusahaan_id')->on('m_kegiatan_perusahaan');
            $table->foreign('periode_id')->references('periode_id')->on('m_periode');

            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable()->index();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable()->index();
            $table->dateTime('deleted_at')->nullable()->index();
            $table->integer('deleted_by')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_pendaftaran');
    }
}
