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
        Schema::create('complaints', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للشكوى');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('المستخدم الذي قدم الشكوى');
            $table->foreignId('booking_id')->nullable()->constrained()->onDelete('set null')->comment('الحجز المرتبط بالشكوى إن وجد');
            $table->string('subject')->comment('موضوع الشكوى');
            $table->text('description')->comment('تفاصيل الشكوى');
            $table->enum('status', ['open', 'in_progress', 'resolved', 'closed'])->default('open')->comment('حالة الشكوى');
            $table->text('resolution')->nullable()->comment('حل الشكوى أو الرد عليها');
            $table->foreignId('resolved_by')->nullable()->constrained('users')->onDelete('set null')->comment('المسؤول الذي حل الشكوى');
            $table->timestamp('resolved_at')->nullable()->comment('تاريخ ووقت حل الشكوى');
            $table->timestamps();
            $table->softDeletes(); // Soft deletes

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};
