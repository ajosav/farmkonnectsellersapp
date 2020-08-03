<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmManagerProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farm_manager_profiles', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->index()->unique();
            $table->string('user_uuid')->index();
            $table->string('farm_size');
            $table->string('state', 50);
            $table->string('lg', 50);
            $table->text('location');
            $table->string('commodities_planted');
            $table->string('contact_person', 50);
            $table->string('c_person_phone', 25);
            $table->string('c_person_email', 50);
            $table->text('c_person_address');
            $table->string('c_person_others')->nullable()->comment('Contact person other information');
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
        Schema::dropIfExists('farm_manager_profiles');
    }
}
