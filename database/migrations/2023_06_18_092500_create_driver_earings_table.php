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
        Schema::create('driver_earings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->integer("trips_num");
            $table->double("captainPrice",10,2);
            $table->double("captainTax",10,2);
            $table->double("earing",10,2);
            $table->double("discount",10,2);
            $table->double("hours",4,2);
            $table->date("day");
            $table->text("day_str");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('driver_earings');
    }
};
