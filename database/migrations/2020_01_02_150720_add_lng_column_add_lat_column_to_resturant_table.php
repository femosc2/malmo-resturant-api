<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLngColumnAddLatColumnToResturantTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('resturant', function (Blueprint $table) {
            Schema::table('resturants', function (Blueprint $table) {
                $table->float('lat')->nullable();
                $table->float('lng')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resturant', function (Blueprint $table) {
            //
        });
    }
}
