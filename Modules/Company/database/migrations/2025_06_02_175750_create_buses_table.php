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
        Schema::create('buses', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للحافلة');
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->comment('الشركة المالكة للحافلة');
            $table->string('plate_number')->unique()->comment('رقم لوحة الحافلة (فريد)');
            $table->string('model')->comment('موديل الحافلة');
            $table->integer('capacity')->comment('السعة الكلية للمقاعد في الحافلة');
            $table->enum('type', ['VIP', 'ECONOMIC'])->comment('نوع الحافلة (VIP أو اقتصادية)');
            $table->text('amenities')->nullable()->comment('المرافق المتوفرة في الحافلة (مثل وايفاي، شاحنات، إلخ)');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buses');
    }
};
