<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payment_transcations', function (Blueprint $table) {
            $table->tinyInteger("is_reserve")->default(0);
            $table->Integer("time")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payment_transcations', function (Blueprint $table) {
            $table->dropColumn("is_reserve");
            $table->dropColumn("time");
        });
    }
};
