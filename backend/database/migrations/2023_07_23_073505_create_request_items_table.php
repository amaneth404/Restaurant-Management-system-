<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('items');
            $table->uuid('item');
            $table->uuid('folder_id');
            $table->uuid('branch');
            $table->integer('amount');
            $table->double('price');
            $table->double('sell_price');
            $table->tinyInteger('state')->default(-1);
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
        Schema::dropIfExists('request_items');
    }
}
