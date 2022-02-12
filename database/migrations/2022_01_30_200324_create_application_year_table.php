<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_year', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('model_id')->nullable()->default(NULL);
            $table->string('name', 20)->nullable()->default(NULL);
            $table->string('slug', 20)->nullable()->default(NULL);
            $table->timestamps();
            $table->foreign('brand_id')->references('id')->on('application_brand')->onDelete('cascade');
            $table->foreign('model_id')->references('id')->on('application_model')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_year');
    }
}
