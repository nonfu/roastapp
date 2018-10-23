<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCafesAddMatchaTea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cafes', function (Blueprint $table) {
            $table->integer('added_by')->after('zip')->unsigned()->nullable();
            $table->tinyInteger('tea')->after('added_by');
            $table->tinyInteger('matcha')->after('tea');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cafes', function (Blueprint $table) {
            $table->dropColumn('added_by');
            $table->dropColumn('matcha');
            $table->dropColumn('tea');
        });
    }
}
