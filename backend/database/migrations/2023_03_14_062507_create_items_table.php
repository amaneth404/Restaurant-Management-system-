<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item');
            $table->uuid('category');
            $table->uuid('branch');
            $table->integer('amount');
            $table->double('price');
            $table->integer('sold')->default(0);
            $table->double('sell_price');
            $table->integer('return')->default(1);
            $table->tinyInteger('isreturn')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->tinyInteger('state')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
