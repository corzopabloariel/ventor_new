<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationTmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('application_tmp', function (Blueprint $table) {
            $table->id();
            $table->string('sku', 40)->nullable()->default(NULL);
            $table->string('brand')->nullable()->default(NULL);
            $table->string('model')->nullable()->default(NULL);
            $table->string('year', 30)->nullable()->default(NULL);
            $table->string('type', 4)->nullable()->default(NULL);
            $table->json('element')->nullable()->default(NULL);
            $table->float('price')->default(0);
            $table->boolean('status')->default(false);
            $table->string('title')->nullable()->default(NULL);
            $table->text('description')->nullable()->default(NULL);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('application_tmp');
    }
}
