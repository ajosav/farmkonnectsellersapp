<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDistanceToDeliveriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            //
            $table->double('distance', 6, 2)->after('destination');
            $table->string('rate')->after('distance');
            $table->date('date')->after('rate');
            $table->string('details', 255)->nullable()->after('date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('deliveries', function (Blueprint $table) {
            //
            $table->dropColumn('distance');
            $table->dropColumn('rate');
            $table->dropColumn('date');
            $table->dropColumn('details');
        });
    }
}