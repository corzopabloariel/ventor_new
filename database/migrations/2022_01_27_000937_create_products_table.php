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
            $table->unsignedBigInteger('part_id')->nullable()->default(NULL);
            $table->unsignedBigInteger('subpart_id')->nullable()->default(NULL);
            $table->string('_id', 50)->nullable()->default(NULL);
            $table->string('nro_original', 70)->nullable()->default(NULL);
            $table->string('use', 70)->nullable()->default(NULL);
            $table->string('stmpdh_art', 70)->nullable()->default(NULL);
            $table->string('codigo_ima', 70)->nullable()->default(NULL);
            $table->text('stmpdh_tex')->nullable()->default(NULL);
            $table->text('name_slug')->nullable()->default(NULL);
            $table->float('precio')->nullable()->default(NULL);
            $table->integer('cantminvta')->default(0);
            $table->integer('stock_mini')->default(0);
            $table->integer('max_ventas')->default(0);
            $table->datetime('fecha_ingr')->nullable()->default(NULL);
            $table->boolean('liquidacion')->default(false);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('part_id')->references('id')->on('parts')->onDelete('cascade');
            $table->foreign('subpart_id')->references('id')->on('subparts')->onDelete('cascade');
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
