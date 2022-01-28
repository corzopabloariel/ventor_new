<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsBrandTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_brand', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('brand_id')->nullable()->default(NULL);
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('brand_id')->references('id')->on('brands')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_brand');
    }
}
