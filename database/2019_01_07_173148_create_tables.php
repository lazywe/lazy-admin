<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableNames = config('lazy-admin.table_names');
        // 创建后台用户表
        Schema::create($tableNames['user'], function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique("email");
            $table->timestamp('email_verified_at')->nullable();
            $table->string('guard_name');
            $table->string('password');
            $table->rememberToken();

            $table->timestamps();
        });
        // 创建目录表
        Schema::create($tableNames['menu'], function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id')->default(0);
            $table->integer('order')->default(0);
            $table->string('title', 50);
            $table->string('icon', 50)->nullable();
            $table->string('uri', 50)->nullable();
            $table->string('roles')->nullable();

            $table->timestamps();
        });
        // 创建日志表
        Schema::create($tableNames['log'], function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->default(0);
            $table->string('ip', 15);
            $table->string('method', 10)->nullable();
            $table->string('uri', 255)->nullable();
            $table->text('params')->nullable();

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
        $tableNames = config('lazy-admin.table_names');
        Schema::dropIfExists($tableNames['user']);
        Schema::dropIfExists($tableNames['menu']);
        Schema::dropIfExists($tableNames['log']);
    }
}
