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
        Schema::create('seats', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للمقعد');
            $table->foreignId('bus_id')->constrained()->onDelete('cascade')->comment('الحافلة التابع لها المقعد');
            $table->string('seat_number')->comment('رقم المقعد داخل الحافلة');
            $table->enum('class', ['VIP', 'ECONOMIC'])->comment('درجة المقعد (VIP أو اقتصادية)');
            $table->boolean('is_available')->default(true)->comment('حالة المقعد (متاح أو محجوز)');
            $table->timestamps();
            $table->softDeletes(); // Soft deletes
            $table->unique(['bus_id', 'seat_number'])->comment('ضمان عدم تكرار رقم المقعد في نفس الحافلة');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seats');
    }
};
