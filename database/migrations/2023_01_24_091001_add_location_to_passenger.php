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
        Schema::table('order_passengers', function (Blueprint $table) {
            $table->unsignedBigInteger("from_id");
            $table->unsignedBigInteger("to_id");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('passenger', function (Blueprint $table) {
            $table->dropColumn("from_id");
            $table->dropColumn("to_id");
        });
    }
};
