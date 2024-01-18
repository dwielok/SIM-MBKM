<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MDosen extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_dosen', function (Blueprint $table) {
            $table->id('dosen_id');
            $table->string('dosen_nip')->nullable()->unique();
            $table->string('dosen_nidn')->nullable()->unique();
            $table->string('dosen_name', 50);
            $table->string('dosen_email', 50)->unique();
            $table->string('dosen_phone', 15);
            $table->enum('dosen_gender', ['L', 'P']);
            $table->integer('jabatan_id')->nullable();
            $table->integer('pangkat_id')->nullable();
            $table->integer('dosen_tahun');
            $table->string('sinta_id')->nullable()->url();
            $table->string('scholar_id')->nullable()->url();
            $table->string('scopus_id')->nullable()->url();
            $table->string('researchgate_id')->nullable()->url();
            $table->string('orcid_id')->nullable()->url();
            $table->unsignedBigInteger('prodi_id')->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->dateTime('created_at')->nullable()->useCurrent();
            $table->integer('created_by')->nullable()->index();
            $table->dateTime('updated_at')->nullable();
            $table->integer('updated_by')->nullable()->index();
            $table->dateTime('deleted_at')->nullable()->index();
            $table->integer('deleted_by')->nullable()->index();

            $table->foreign('prodi_id')->references('prodi_id')->on('m_prodi');
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
