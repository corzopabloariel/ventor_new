<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHashfileTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hashfile', function (Blueprint $table) {
            $table->id();
            $table->string('word');
            $table->string('hash');
            $table->integer('total')->default(0)->comment('Descargas efectuadas por el Hash');
            $table->boolean('test')->default(false);
            $table->softDeletes();
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
        Schema::dropIfExists('hashfile');
    }
}
