<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserLoginHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_login_histories', function (Blueprint $table) {
            $table->uuid('id');
            $table->UUID('user_id');
            $table->text('token');
            $table->String('browser')->nullable();
            $table->String('mobile')->nullable();
            $table->boolean('isMobile')->default(false);
            $table->String('computer')->nullable();
            $table->boolean('isBrowser')->default(true);
            $table->String('application')->nullable();
            $table->timestamps();
            $table->softDeletes()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_login_histories');
    }
}
