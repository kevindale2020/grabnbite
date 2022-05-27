<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDatesColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            
            $table->dateTime('confirmed_date')->nullable()->default(null);
            $table->dateTime('cancelled_date')->nullable()->default(null);
            $table->dateTime('delivered_date')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            
            $table->dropColumn('confirmed_date');
            $table->dropColumn('cancelled_date');
            $table->dropColumn('delivered_date');
        });
    }
}
