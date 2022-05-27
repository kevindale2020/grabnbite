<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
            $table->id();
            $table->string('image', 255)->default('user_none.png');
            $table->string('fname', 50)->default('');
            $table->string('lname', 50)->default('');
            $table->string('address', 100)->default('');
            $table->string('email', 50)->unique();
            $table->string('phone', 20)->default('');
            $table->string('password', 255);
            $table->rememberToken();
            $table->timestamps();
            $table->tinyInteger('is_verified')->default(0);
            $table->dateTime('verified_at')->nullable()->default(null);
            $table->string('vkey', 64)->default('');
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
