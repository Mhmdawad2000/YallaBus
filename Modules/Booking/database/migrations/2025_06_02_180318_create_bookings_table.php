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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للحجز');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('المستخدم الذي قام بالحجز');
            $table->foreignId('trip_id')->constrained()->onDelete('cascade')->comment('الرحلة المحجوزة');
            $table->string('booking_reference')->unique()->comment('الرقم المرجعي للحجز (يظهر للمستخدم)');
            $table->decimal('total_price', 8, 2)->comment('المبلغ الإجمالي للحجز');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed'])->default('pending')->comment('حالة الحجز');
            $table->timestamp('cancelled_at')->nullable()->comment('تاريخ ووقت إلغاء الحجز إن وجد');
            $table->text('cancellation_reason')->nullable()->comment('سبب إلغاء الحجز إن وجد');
            $table->timestamps();
            $table->softDeletes(); // Soft deletes

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
