<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('logs', function (Blueprint $table) {
            //
            $table->string('api_name')->after('id')->nullable();
            $table->text('request')->after('api_name')->nullable();
            $table->string('status_code')->after('request')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('logs', function (Blueprint $table) {
            //
            $table->dropColumn(['api_name', 'request', 'status_code']);
        });
    }
};
