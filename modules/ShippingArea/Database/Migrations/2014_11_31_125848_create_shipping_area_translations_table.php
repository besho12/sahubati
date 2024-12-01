<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShippingAreaTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shipping_area_translations', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('shipping_area_id');
            $table->string('locale');
            $table->string('name');

            $table->unique(['shipping_area_id', 'locale']);
            $table->foreign('shipping_area_id')->references('id')->on('shipping_areas')->onDelete('cascade');
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shipping_areas_translations');
    }
}
