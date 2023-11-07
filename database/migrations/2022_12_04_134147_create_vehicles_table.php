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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->foreignId("vehicle_types_id");
            $table->foreignId("Vehicle_color_id");
            $table->string("purchase_year");
            $table->string("car_number");
            $table->unsignedBigInteger("user_id")->nullable();
            $table->unsignedBigInteger("admin_id")->nullable();
            $table->unsignedBigInteger("owner_id")->nullable();
            $table->tinyInteger("is_approve")->default(0);
            $table->tinyInteger("is_active")->default(1);
            $table->string("action")->nullable();
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
        Schema::dropIfExists('vehicles');
    }
};
