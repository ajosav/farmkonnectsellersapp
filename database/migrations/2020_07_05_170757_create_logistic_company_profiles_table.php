<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLogisticCompanyProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logistic_company_profiles', function (Blueprint $table) {
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
            $table->string('driving_license');
            $table->string('vehicle_reg_no', 50);
            $table->string('chasis', 100);
            $table->string('license_expiration', 100);
            $table->text('valid_vehicle_doc')->comment('json of documents and expiration');
            $table->longText('other_info')->nullable();
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
        Schema::dropIfExists('logistic_company_profiles');
    }
}
