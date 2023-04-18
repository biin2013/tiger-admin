<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUsersTable extends Migration
{
    public function up()
    {
        Schema::create(config('tiger.admin.database.user_table'), function (Blueprint $table) {
            $table->id();
            $table->string('username', 45);
            $table->string('password', 100);
            $table->string('nickname', 45)->default('');
            $table->unsignedTinyInteger('status')->default(1)->index('status');
            $table->unsignedBigInteger('login_times')->default(0);
            $table->timestamp('last_login_at')->nullable();
            $table->timestamp('update_password_at')->nullable();
            $table->string('brief', 255)->default('');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('tiger.admin.database.user_table'));
    }
}