<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id(); // المعرف الفريد للمستخدم
            $table->string('first_name')->comment('اسم المستخدم الاول');
            $table->string('last_name')->comment('اسم المستخدم الاخير');
            $table->string('email')->unique()->comment('البريد الإلكتروني للمستخدم (يستخدم لتسجيل الدخول)');
            $table->timestamp('email_verified_at')->nullable()->comment('تاريخ وتوقيت التحقق من البريد الإلكتروني');
            $table->string('password')->comment('كلمة المرور مشفرة');
            $table->string('phone')->nullable()->comment('رقم الهاتف المحمول');
            $table->enum('role', ['user', 'admin', 'support', 'company'])->default('user')->comment('دور المستخدم في النظام');
            $table->rememberToken()->comment('توكن للتذكر');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
