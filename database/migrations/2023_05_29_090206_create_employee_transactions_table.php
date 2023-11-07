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
        Schema::create('employee_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("employee_id")->comment("this have user_id made this action");
            $table->unsignedBigInteger("user_id")->comment("this have user_id get this transaction");
            $table->string("type");
            $table->date("date");
            $table->double("amount",10,2);
            $table->boolean("is_collected")->default(0);
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
        Schema::dropIfExists('employee_transactions');
    }
};
