<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MTipeKegiatan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_jenis_program', function (Blueprint $table) {
            $table->id('jenis_program_id');
            $table->string('nama_program');
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
        Schema::dropIfExists('m_jenis_program');
    }
}
