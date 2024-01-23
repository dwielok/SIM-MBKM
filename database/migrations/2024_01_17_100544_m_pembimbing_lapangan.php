<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MPembimbingLapangan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_pembimbing_lapangan', function (Blueprint $table) {
            $table->id('pembimbing_lapangan_id');
            // isi tabel pembimbinglapangan
            $table->string('name_pembimbing_lapangan');
            $table->string('jabatan_pembimbing_lapangan');
            $table->string('tempat_industri_pembimbing_lapangan');
            $table->string('phone_pembimbing_lapangan');
            $table->string('email_pembimbing_lapangan')->unique();
            // foto
            $table->unsignedBigInteger('user_id')->index();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable()->index();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable()->index();
            $table->dateTime('deleted_at')->nullable()->index();
            $table->integer('deleted_by')->nullable()->index();

            $table->foreign('user_id')->references('user_id')->on('s_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
