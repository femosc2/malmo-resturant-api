<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resturant_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->integer('reports')->unsigned()->default(0);
            $table->boolean('is_bad')->unsigned()->default(False);
            $table->unsignedBigInteger('resturant_id');
            $table->foreign('resturant_id')->references('id')->on('resturants')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('item_images', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('image');
            $table->integer('reports')->unsigned()->default(0);
            $table->boolean('is_bad')->unsigned()->default(False);
            $table->unsignedBigInteger('item_id');
            $table->foreign('item_id')->references('id')->on('items');
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
        Schema::dropIfExists('resturant_images');
        Schema::dropIfExists('item_images');
    }
}
