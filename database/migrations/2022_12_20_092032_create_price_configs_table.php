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
        Schema::create('price_configs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("shipment_type_id");
            $table->unsignedBigInteger("ride_types_id");
            $table->foreign("ride_types_id")->on("ride_types")->references("id")->cascadeOnDelete();

            $table->double("km_price",6,2);
            $table->double("waiting_time_price",6,2);
            $table->double("traffic_jam_price",6,2);
            $table->double("move_minute_price",6,2);
            $table->string("appKey");
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
        Schema::dropIfExists('price_configs');
    }
};
