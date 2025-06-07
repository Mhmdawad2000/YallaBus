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
        Schema::create('booking_seats', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد لسجل المقعد المحجوز');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade')->comment('الحجز المرتبط');
            $table->foreignId('seat_id')->constrained()->onDelete('cascade')->comment('المقعد المحجوز');
            $table->decimal('price', 8, 2)->comment('سعر المقعد وقت الحجز');
            $table->timestamps();
            $table->softDeletes(); // Soft deletes

            $table->unique(['booking_id', 'seat_id'])->comment('ضمان عدم تكرار المقعد في نفس الحجز');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_seats');
    }
};
