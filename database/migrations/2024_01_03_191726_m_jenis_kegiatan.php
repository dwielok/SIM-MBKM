<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MJenisMagang extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_jenis_kegiatan', function (Blueprint $table) {
            $table->id('jenis_kegiatan_id');
            $table->unsignedBigInteger('jenis_program_id');
            $table->foreign('jenis_program_id')->references('jenis_program_id')->on('m_jenis_program');
            $table->string('nama_kegiatan');
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
        Schema::dropIfExists('m_jenis_kegiatan');
    }
}
