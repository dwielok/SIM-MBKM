<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MPerusahaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_perusahaan', function (Blueprint $table) {
            $table->id('perusahaan_id');
            $table->string('nama_perusahaan', 100);
            $table->string('logo')->nullable();
            $table->string('kategori');
            $table->string('tipe_industri');
            $table->text('alamat');
            $table->integer('provinsi_id');
            $table->integer('kota_id');
            $table->text('profil_perusahaan');
            $table->string('website');
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
        Schema::dropIfExists('m_perusahaan');
    }
}
