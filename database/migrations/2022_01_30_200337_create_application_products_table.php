<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('application_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('brand_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('model_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('year_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('product_id')->nullable()->default(NULL);
            $table->enum('type', ['CONDUCTOR', 'ACOMPAÑANTE', 'TRASERA']);
            $table->timestamps();
            $table->foreign('application_id')->references('id')->on('application_tmp')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('application_brand')->onDelete('cascade');
            $table->foreign('model_id')->references('id')->on('application_model')->onDelete('cascade');
            $table->foreign('year_id')->references('id')->on('application_year')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_products');
    }
}
