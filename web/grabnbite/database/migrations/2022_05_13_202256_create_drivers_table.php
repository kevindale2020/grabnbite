<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('vehicle_type', 50)->default('');
            $table->string('vehicle_color', 50)->default('');
            $table->string('vehicle_plate_no', 50)->default('');
            $table->string('tin', 255)->default('');
            $table->string('bir_form', 255)->default('');
            $table->string('gov_issued_id', 255)->default('');
            $table->string('driver_license', 255)->default('');
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
        Schema::dropIfExists('drivers');
    }
}
