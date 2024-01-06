<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TPerusahaan2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_perusahaan', function (Blueprint $table) {
            $table->integer('status')->comment('0: Menunggu, 1: Diterima, 2: Ditolak')->after('website')->default(0)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_perusahaan', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
