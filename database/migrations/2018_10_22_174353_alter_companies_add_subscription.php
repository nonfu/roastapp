<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompaniesAddSubscription extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->tinyInteger('subscription')->defualt(0)->after('roaster');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('subscription');
        });
    }
}
