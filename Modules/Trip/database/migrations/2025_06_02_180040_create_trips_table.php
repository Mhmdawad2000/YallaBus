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
        Schema::create('trips', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للرحلة');
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->comment('الشركة المنظمة للرحلة');
            $table->foreignId('bus_id')->constrained()->onDelete('cascade')->comment('الحافلة المستخدمة في الرحلة');
            $table->foreignId('driver_id')->constrained()->onDelete('cascade')->comment('السائق المسؤول عن الرحلة');
            $table->string('departure_city')->comment('مدينة المغادرة');
            $table->string('arrival_city')->comment('مدينة الوصول');
            $table->dateTime('departure_time')->comment('تاريخ ووقت المغادرة');
            $table->dateTime('arrival_time')->comment('تاريخ ووقت الوصول المتوقع');
            $table->decimal('price', 8, 2)->comment('سعر الرحلة');
            $table->integer('available_seats')->comment('عدد المقاعد المتاحة');
            $table->enum('status', ['available', 'cancelled', 'delayed', 'completed'])->default('available')->comment('حالة الرحلة');
            $table->text('notes')->nullable()->comment('ملاحظات إضافية عن الرحلة');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
