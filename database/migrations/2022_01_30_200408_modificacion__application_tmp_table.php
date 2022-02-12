<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModificacionApplicationTmpTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('application_tmp', function (Blueprint $table) {
            $table->dropColumn('brand');
            $table->dropColumn('model');
            $table->dropColumn('year');
            $table->dropColumn('type');
            $table->dropColumn('element');
            $table->unsignedBigInteger('brand_id')->nullable()->default(NULL)->after('id');
            $table->unsignedBigInteger('model_id')->nullable()->default(NULL)->after('id');
            $table->unsignedBigInteger('year_id')->nullable()->default(NULL)->after('id');
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
        Schema::table('application_tmp', function (Blueprint $table) {
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['model_id']);
            $table->dropForeign(['year_id']);
            $table->dropColumn('brand_id');
            $table->dropColumn('model_id');
            $table->dropColumn('year_id');
            $table->string('brand')->nullable()->default(NULL);
            $table->string('model')->nullable()->default(NULL);
            $table->string('year', 30)->nullable()->default(NULL);
            $table->string('type', 4)->nullable()->default(NULL);
            $table->json('element')->nullable()->default(NULL);
        });
    }
}
