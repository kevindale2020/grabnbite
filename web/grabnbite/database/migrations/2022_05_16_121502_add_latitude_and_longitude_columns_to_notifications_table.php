<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLatitudeAndLongitudeColumnsToNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            
            $table->decimal('latitude', 11, 5)->nullable();
            $table->decimal('longitude', 11, 5)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            
            $table->dropColumn('latitude');
            $table->dropColumn('longitude');
        });
    }
}
