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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();

            $table->nullableMorphs('updateBy');
            $table->softDeletes();
            $table->text('email')->nullable();
            $table->text('phone')->nullable();

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
        Schema::dropIfExists('settings_content');
        Schema::dropIfExists('settings');
    }
};
