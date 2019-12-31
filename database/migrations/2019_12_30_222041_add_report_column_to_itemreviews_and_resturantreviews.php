<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddReportColumnToItemreviewsAndResturantreviews extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_reviews', function (Blueprint $table) {
            $table->integer('reports')->unsigned()->default(0);
            $table->boolean('is_bad')->unsigned()->default(False);
        });
        Schema::table('resturant_reviews', function (Blueprint $table) {
            $table->integer('reports')->unsigned()->default(0);
            $table->boolean('is_bad')->unsigned()->default(False);
        });
        Schema::table('resturants', function (Blueprint $table) {
            $table->integer('reports')->unsigned()->default(0);
            $table->boolean('is_bad')->unsigned()->default(False);
        });
        Schema::table('items', function (Blueprint $table) {
            $table->integer('reports')->unsigned()->default(0);
            $table->boolean('is_bad')->unsigned()->default(False);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table) {
            //
        });
    }
}
