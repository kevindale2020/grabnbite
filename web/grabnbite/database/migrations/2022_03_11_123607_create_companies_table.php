<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('image', 255)->default('company_none.png');
            $table->string('name', 50)->nullable()->default('');
            $table->string('description', 100)->nullable()->default('');
            $table->string('location', 100)->nullable()->default('');
            $table->decimal('latitude', 11, 5)->nullable();
            $table->decimal('longitude', 11, 5)->nullable();
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
        Schema::dropIfExists('companies');
    }
}
