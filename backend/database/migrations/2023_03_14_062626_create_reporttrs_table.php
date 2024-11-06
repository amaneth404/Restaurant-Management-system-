<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReporttrsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reporttrs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('item');
            $table->uuid('items');
            $table->uuid('branch');
            $table->integer('amount');
            $table->tinyInteger('isreturn')->default(0);
            $table->tinyInteger('type')->default(0);
            $table->double('price');
            $table->double('sell_price');
            $table->uuid('isreported')->default(0);
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
        Schema::dropIfExists('reporttrs');
    }
}
