<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModificacionEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->string('send_by', 40)->nullable()->default(NULL)->after('type');
            $table->string('from', 255)->nullable()->default(NULL)->after('send_by');
            $table->string('to', 255)->nullable()->default(NULL)->after('from');
            $table->string('subject', 255)->nullable()->default(NULL)->after('to');
            $table->text('body')->nullable()->default(NULL)->after('subject');
            $table->boolean('is_order')->default(false)->after('use');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('emails', function (Blueprint $table) {
            $table->dropColumn('send_by');
            $table->dropColumn('from');
            $table->dropColumn('to');
            $table->dropColumn('subject');
            $table->dropColumn('body');
            $table->dropColumn('is_order');
        });
    }
}
