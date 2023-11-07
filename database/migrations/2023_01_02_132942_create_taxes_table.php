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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->unsignedBigInteger("country_id");
            $table->foreign("country_id")->on("countries")->references("id")->cascadeOnDelete();

            $table->tinyInteger("tax_type")->comment("1 fixed 2 percentage");
            $table->string("type")->comment("1 user 2 captain 3 order");
            $table->unsignedBigInteger("type_id")->nullable();
            $table->integer("value");
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
        Schema::dropIfExists('taxes');
    }
};
