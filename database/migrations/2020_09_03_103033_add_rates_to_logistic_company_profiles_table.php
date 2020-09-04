<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRatesToLogisticCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logistic_company_profiles', function (Blueprint $table) {
            //
            $table->double('rate', 9, 2)->after('email')->comments('Rate Per KM');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logistic_company_profiles', function (Blueprint $table) {
            //
            $table->dropColumn('rate');
        });
    }
}