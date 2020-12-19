<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emails', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 24)->nullable()->default(NULL)->comment('MongoDB');
            $table->string('type', 5)->nullable()->default(NULL)->comment('SMTP, API ...');
            $table->boolean('sent')->default(false)->comment('Si se envió el mensaje');
            $table->boolean('error')->default(false)->comment('Si hubo un error en el intento de envio');
            $table->tinyInteger('use')->default(0)->comment('0: desde el sistema / 1: desde el portal');
            $table->ipAddress('ip')->nullable()->default(NULL);
            $table->text('user_agent')->nullable()->default(NULL);
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
        Schema::dropIfExists('emails');
    }
}
