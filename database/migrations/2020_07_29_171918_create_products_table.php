<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('name');
            $table->string('code');
            $table->string('quantity');
            $table->string('image');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('price')->nullable()->comment('per the unit selected');
            $table->integer('unit_id');
            $table->integer('purchase_unit_id');
            $table->integer('sale_unit_id');
            $table->longText('description');
            $table->string('category')->comment('from commodities table');
            $table->tinyInteger('status')->comment('0: default, 1: ordered, 2: sold')->default(0);
            $table->string('created_by');
            $table->timestamps();

            $table->softDeletes();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
