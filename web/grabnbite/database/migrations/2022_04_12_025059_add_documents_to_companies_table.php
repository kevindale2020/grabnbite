<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDocumentsToCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('business_permit', 255);
            $table->string('dti_cert', 255);
            $table->string('dti_form', 255);
            $table->string('valid_id', 255);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('business_permit');
            $table->dropColumn('dti_cert');
            $table->dropColumn('dti_form');
            $table->dropColumn('valid_id');
        });
    }
}
