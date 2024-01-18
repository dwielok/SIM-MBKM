<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MLogBimbingan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_log_bimbingan', function (Blueprint $table) {
            $table->id('log_bimbingan_id');
            // isi tabel pembimbinglapangan
            $table->string('topic');
            $table->longText('log_content');
            $table->date('Log_date');
            $table->time('log_time_start');
            $table->time('log_time_end');
            $table->string('photo');
            $table->Integer('status1');
            $table->Integer('status2');
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
