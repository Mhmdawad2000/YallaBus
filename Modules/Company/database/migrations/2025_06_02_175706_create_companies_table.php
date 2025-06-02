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
        Schema::create('companies', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للشركة');
            $table->string('name')->comment('اسم الشركة');
            $table->text('description')->nullable()->comment('وصف الشركة وخدماتها');
            $table->string('logo')->nullable()->comment('مسار صورة شعار الشركة');
            $table->string('contact_email')->comment('البريد الإلكتروني للتواصل مع الشركة');
            $table->string('contact_phone')->comment('رقم هاتف التواصل مع الشركة');
            $table->foreignId('admin_id')->constrained('users')->onDelete('cascade')->comment('المسؤول عن الشركة (مرتبط بجدول المستخدمين)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
