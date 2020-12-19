<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('type')->default(1)->comment('1: A / 2: B / 3: M');
            $table->string('table', 40)->nullable()->default(NULL)->comment('Tabla afectada');
            $table->integer('table_id')->nullable()->default(NULL)->comment('Tabla ID');
            $table->text('obs')->nullable()->default(NULL);
            $table->unsignedBigInteger('user_id')->nullable()->default(NULL);
            
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('tickets');
    }
}
