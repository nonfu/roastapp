<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCafesDropCompanyColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cafes', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->dropColumn('roaster');
            $table->dropColumn('website');
            $table->dropColumn('description');
            $table->dropColumn('added_by');
            $table->dropColumn('parent');
            $table->integer('company_id')->unsigned()->default(0);
            $table->softDeletes();
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
            $table->string('name')->after('id');
            $table->integer('roaster')->after('longitude');
            $table->text('website')->after('roaster');
            $table->text('description')->after('website');
            $table->integer('added_by')->after('description')->unsigned()->nullable();
            $table->integer('parent')->unsigned()->nullable()->after('id');
            $table->dropColumn('company_id');
            $table->dropColumn('deleted_at');
        });
    }
}
