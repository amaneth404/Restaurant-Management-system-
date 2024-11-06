<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cashers', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->String('table_name')->nullable();
            $table->String('phone_number')->nullable();
            $table->uuid('waiter')->nullable();
            $table->tinyInteger('isflaged')->default(0);
            $table->tinyInteger('isdelivery')->default(0);
            $table->double('fee')->nullable();
            $table->double('price');
            $table->integer('orders')->default(0);
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
        Schema::dropIfExists('cashers');
    }
}
