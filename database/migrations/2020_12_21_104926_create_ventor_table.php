<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVentorTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ventor', function (Blueprint $table) {
            $table->id();
            $table->json('address')->nullable()->default(NULL);
            $table->json('captcha')->nullable()->default(NULL);
            $table->json('phone')->nullable()->default(NULL);
            $table->json('email')->nullable()->default(NULL);
            $table->json('social')->nullable()->default(NULL);
            $table->json('metadata')->nullable()->default(NULL);
            $table->json('images')->nullable()->default(NULL);
            $table->json('section')->nullable()->default(NULL);
            $table->json('miscellaneous')->nullable()->default(NULL);
            $table->json('forms')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventor');
    }
}
