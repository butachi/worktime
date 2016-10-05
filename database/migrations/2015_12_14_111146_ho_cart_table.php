<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class HoCartTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('ho_cart')) {
            Schema::create('ho_cart', function (Blueprint $table) {
                $table->increments('id');
                $table->bigInteger('customer_id');
                $table->string('session_id', 32);
                $table->text('option');
                $table->integer('quantity');
                $table->datetime('created_at_jp');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ho_cart');
    }
}
