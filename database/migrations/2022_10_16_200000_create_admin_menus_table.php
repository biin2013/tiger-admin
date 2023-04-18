<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminMenusTable extends Migration
{
    public function up()
    {
        Schema::create(config('tiger.admin.database.menu_table'), function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pid')->index('pid');
            $table->string('name', 20);
            $table->json('full_name');
            $table->string('icon')->default('');
            $table->string('url', 255);
            $table->unsignedTinyInteger('seq')->default(128)->index('seq');
            $table->unsignedBigInteger('permission_id')->index('permission_id');
            $table->string('locale', 10)->index('locale');
            $table->unsignedTinyInteger('keep_alive')->default(1);
            $table->string('redirect', 255)->default('');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists(config('tiger.admin.database.menu_table'));
    }
}