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
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger("promo_type")->comment("1 voucher 2 coupon");
            $table->string("title");
            $table->string("code");
            $table->tinyInteger("type")->comment("1 fixed 2 percentage");
            $table->integer("discount");
            $table->integer("num_of_use")->default(1);
            $table->integer("min_amount")->default(0);
            $table->integer("max_amount")->default(0);
            $table->string("status");
            $table->tinyInteger("applied_type")->comment("1 allUser 2 one_user 3 order 4 country ");
            $table->unsignedBigInteger("applied_id")->nullable();
            $table->date("start_at");
            $table->date("expire_at");
            $table->string("appKey");
            $table->timestamps();
            $table->unique(["code","appKey"]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_codes');
    }
};
