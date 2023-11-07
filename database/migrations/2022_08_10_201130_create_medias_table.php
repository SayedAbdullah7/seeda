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
        Schema::create('medias', function (Blueprint $table) {
            $table->id();
            // $table->integer('imageable_id');
            $table->morphs('mediaable');
            $table->string('filename');
            $table->string('filetype')->default('image');
            $table->string('type');
            $table->softDeletes('deleted_at', 0);

            $table->morphs('createBy');
            $table->nullableMorphs('updateBy');

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
        Schema::dropIfExists('images');
    }
};
