<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id('user_id');
            $table->string('nama_user');
            $table->string('email');
            $table->string('avatar')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('no_hp')->nullable();
            $table->string('password');
            $table->string('password_confirmation');
            $table->string('posisi')->nullable();
            $table->enum('role', ['superadmin', 'admin', 'karyawan'])->default('karyawan');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
