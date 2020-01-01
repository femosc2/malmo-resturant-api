<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<< HEAD:database/migrations/2019_12_29_001552_create_resturants_table.php
        Schema::create('resturants', function (Blueprint $table) {
            $table->bigIncrements('id')->onDelete('cascade');
=======
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
>>>>>>> 44e7a77dbfacb46eddd275252e5bb48783675007:database/migrations/2014_10_12_000000_create_users_table.php
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
