<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MKegiatanPerusahaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_kegiatan_perusahaan', function (Blueprint $table) {
            $table->id('kegiatan_perusahaan_id');
            $table->unsignedBigInteger('perusahaan_id')->index();
            $table->string('kode_kegiatan', 10)->unique();
            $table->unsignedBigInteger('tipe_kegiatan_id')->index();
            $table->unsignedBigInteger('jenis_magang_id')->nullable()->index();
            $table->string('posisi_lowongan');
            $table->text('deskripsi');
            $table->integer('kuota');
            $table->date('mulai_kegiatan');
            $table->date('akhir_kegiatan');
            $table->integer('status')->comment('0: Menunggu, 1: Diterima, 2: Ditolak');
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable()->index();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable()->index();
            $table->dateTime('deleted_at')->nullable()->index();
            $table->integer('deleted_by')->nullable()->index();

            $table->foreign('perusahaan_id')->references('perusahaan_id')->on('m_perusahaan');
            $table->foreign('tipe_kegiatan_id')->references('tipe_kegiatan_id')->on('m_tipe_kegiatan');
            $table->foreign('jenis_magang_id')->references('jenis_magang_id')->on('m_jenis_magang');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('m_kegiatan_perusahaan');
    }
}
