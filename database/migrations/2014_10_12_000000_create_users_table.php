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
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('roleID');
            $table->string('address');
            $table->string('phone');
            $table->string('bio')->nullable();
            $table->string('image')->default("http://oi1065.photobucket.com/albums/u395/monkeyzealer/794180f2868e8d2e79cb08dc5c874a73_zpsik9y0hmm.png");
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
