<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterUsersAddProfileFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->text('favorite_coffee')->after('avatar')->comment('最喜欢的咖啡');
            $table->text('flavor_notes')->after('favorite_coffee')->comment('口味记录');
            $table->boolean('profile_visibility')->default('1')->after('flavor_notes')->comment('是否公开个人信息');
            $table->string('city')->after('profile_visibility')->comment('所在城市');
            $table->string('state')->after('city')->comment('所在省份');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('favorite_coffee');
            $table->dropColumn('flavor_notes');
            $table->dropColumn('profile_visibility');
            $table->dropColumn('city');
            $table->dropColumn('state');
        });
    }
}
