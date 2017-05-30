<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Orders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('orders', function (Blueprint $table) {
          $table->increments('id');
          $table->integer('userID');
          $table->integer('productID');
          $table->integer('amount')->default(1);
          $table->integer('totalPrice');
          $table->longText('comment')->nullable();
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
        Schema::drop("orders");
    }
}
