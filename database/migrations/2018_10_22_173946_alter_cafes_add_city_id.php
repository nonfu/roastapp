<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCafesAddCityId extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cafes', function (Blueprint $table) {
            $table->integer('city_id')->after('location_name')->unsigned()->nullable();
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
            $table->dropColumn('city_id');
        });
    }
}
