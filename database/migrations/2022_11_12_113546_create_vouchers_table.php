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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string("title");
            $table->string("code");
            $table->enum("type",["fixed","percentage","cash"]);
            $table->double("discount");
            $table->string("appKey");
            $table->string("status");
            $table->date("start_at");
            $table->date("expire_at");
            $table->unique(['code',"appKey"]);
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
        Schema::dropIfExists('vouchers');
    }
};
