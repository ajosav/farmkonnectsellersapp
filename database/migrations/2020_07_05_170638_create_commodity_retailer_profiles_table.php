<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommodityRetailerProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commodity_retailer_profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index()->unique();
            $table->string('user_uuid')->index();
            $table->string('name', 50);
            $table->string('state', 50);
            $table->string('lg', 50);
            $table->text('address');
            $table->string('location', 100);
            $table->string('phone', 25);
            $table->string('email', 50);
            $table->text('other_info')->comment('json of other information')->nullable();
            $table->timestamps();

            $table->softDeletes();
            $table->foreign('user_uuid')->references('uuid')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commodity_retailer_profiles');
    }
}
