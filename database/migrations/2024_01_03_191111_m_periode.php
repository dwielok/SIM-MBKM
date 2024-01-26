<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MPeriode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_periode', function (Blueprint $table) {
            $table->id('periode_id');
            $table->string('semester');
            $table->string('tahun_ajar');
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
        Schema::dropIfExists('m_periode');
    }
}
