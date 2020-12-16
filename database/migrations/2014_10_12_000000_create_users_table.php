<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('uid', 24)->nullable()->default(NULL);
            $table->string('name');
            $table->string('docket', 20)->nullable()->default(NULL);
            $table->string('email', 150)->nullable()->default(NULL);
            $table->string('phone', 100)->nullable()->default(NULL);
            $table->string('username', 20)->unique();
            $table->string('password');
            $table->enum('role', ['EMP', 'VND', 'USR', 'ADM']);
            $table->float('discount')->default(0)->comment('Descuento');
            $table->date('start')->nullable()->default(NULL)->comment('Inicio de incorporaciones');
            $table->date('end')->nullable()->default(NULL)->comment('Fin de incorporaciones');
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
