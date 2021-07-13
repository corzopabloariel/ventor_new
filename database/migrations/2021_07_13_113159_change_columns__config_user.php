<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsConfigUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('config_user', function (Blueprint $table) {
            $table->dropForeign('config_user_user_id_foreign');
            $table->dropColumn('user_id');
            $table->string('username', 20)->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('config_user', function (Blueprint $table) {
            //
        });
    }
}
