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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->softDeletes('deleted_at', 0);

            $table->nullableMorphs('createBy');
            $table->nullableMorphs('updateBy');

            $table->timestamps();
            $table->string('appKey');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notify');
        Schema::dropIfExists('notifications');
    }
};
