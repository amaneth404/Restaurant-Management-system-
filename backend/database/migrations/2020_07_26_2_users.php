<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
class Users extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->String('username');
            $table->String('email')->nullable();
            $table->String('password');
            $table->String('role');
            $table->String('phone_number')->nullable();
            $table->String('first_name')->nullable();
            $table->String('last_name')->nullable();
            $table->String('middle_name')->nullable();
            $table->String('image')->nullable();
            $table->timestamp('username_change_at')->nullable();
            $table->tinyInteger('state')->default(1);
            $table->timestamps();
            $table->softDeletes()->nullable();
            
        });

    }

    public function down()
    {
        Schema::dropIfExists('users');
        
    }
}
