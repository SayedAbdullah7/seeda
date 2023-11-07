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
        Schema::table('order_details', function (Blueprint $table) {
            $table->double("captainTax",6,2)->nullable();
            $table->double("userTax",6,2)->nullable();
            $table->double("captainPrice",6,2)->nullable();
            $table->double("userPrice",6,2)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('order_details', function (Blueprint $table) {
            $table->dropColumn("captainTax");
            $table->dropColumn("userTax");
            $table->dropColumn("captainPrice");
            $table->dropColumn("userPrice");
        });
    }
};
