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
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للدفع');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade')->comment('الحجز المرتبط بالدفع');
            $table->string('transaction_id')->unique()->comment('رقم المعاملة المالي (مرجع الدفع)');
            $table->decimal('amount', 8, 2)->comment('المبلغ المدفوع');
            $table->string('payment_method')->comment('طريقة الدفع (بطاقة، تحويل بنكي، إلخ)');
            $table->enum('status', ['pending', 'completed', 'failed', 'refunded'])->default('pending')->comment('حالة الدفع');
            $table->text('payment_details')->nullable()->comment('تفاصيل إضافية عن الدفع (قد تحتوي بيانات البطاقة المشفرة)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
