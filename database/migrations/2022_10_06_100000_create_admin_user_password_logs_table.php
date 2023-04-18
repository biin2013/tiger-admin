<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminUserPasswordLogsTable extends Migration
{
    public function up()
    {
        Schema::create('admin_user_password_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->index('user_id');
            $table->string('password', 100);
            $table->timestamp('created_at')->index('created_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('admin_user_password_logs');
    }
}