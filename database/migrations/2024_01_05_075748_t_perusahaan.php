<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TPerusahaan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //add user_id in m_perusahaan reference user_id in m_user nullable
        Schema::table('m_perusahaan', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->index()->after('perusahaan_id');
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
        //remove user_id in m_perusahaan
        Schema::table('m_perusahaan', function (Blueprint $table) {
            $table->dropForeign('m_perusahaan_user_id_foreign');
            $table->dropColumn('user_id');
        });
    }
}
