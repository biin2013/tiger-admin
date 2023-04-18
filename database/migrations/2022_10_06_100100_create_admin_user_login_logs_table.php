<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUserLoginLogsTable extends Migration
{
    public function up()
    {
        Schema::create('admin_user_login_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->default(0)->index('user_id');
            $table->string('username', 45)->index('username');
            $table->unsignedBigInteger('ip');
            $table->string('agent')->default('');
            $table->unsignedTinyInteger('status')->index('status');
            $table->timestamp('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_user_login_logs');
    }
}