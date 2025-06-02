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
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للسجل');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null')->comment('المستخدم المسؤول عن الحدث إن وجد');
            $table->string('action')->comment('العملية التي تم تسجيلها');
            $table->text('details')->nullable()->comment('تفاصيل إضافية عن الحدث');
            $table->string('ip_address')->nullable()->comment('عنوان IP مصدر الحدث');
            $table->string('user_agent')->nullable()->comment('معلومات المتصفح أو الجهاز');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
