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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للسائق');
            $table->foreignId('company_id')->constrained()->onDelete('cascade')->comment('الشركة التابع لها السائق');
            $table->string('name')->comment('اسم السائق الكامل');
            $table->string('license_number')->unique()->comment('رقم رخصة القيادة (فريد)');
            $table->string('phone')->comment('رقم هاتف السائق للتواصل');
            $table->string('photo')->nullable()->comment('مسار صورة السائق');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
