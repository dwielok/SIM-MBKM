<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MSeminarHasil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_seminar_hasil', function (Blueprint $table) {
            $table->id('seminar_hasil_id');
            // isi tabel seminar hasil
            // judul
            // file proposal
            // file ppt
            // link github 
            $table->longText('judul');
            $table->string('file_proposal'); // You can adjust the type if needed
            $table->string('file_ppt');
            $table->string('link_github')->nullable()->url();
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
        //
    }
}
