<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResturantReviewTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resturant_reviews', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reviewer', 30);
            $table->integer('rating')->unsigned();
            $table->string('review', 1000);
            $table->unsignedBigInteger('resturant_id');
            $table->foreign('resturant_id')->references('id')->on('resturants')->onDelete('cascade');
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
        Schema::dropIfExists('resturant_review');
    }
}
