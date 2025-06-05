<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('reviews', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للتقييم');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('المستخدم الذي قدم التقييم');
            $table->foreignId('trip_id')->constrained()->onDelete('cascade')->comment('الرحلة التي تم تقييمها');
            $table->integer('rating')->comment('تقييم الرحلة من 1 إلى 5 نجوم');
            $table->text('comment')->nullable()->comment('تعليق المستخدم على الرحلة');
            $table->timestamps();
            $table->softDeletes(); // Soft deletes
            
            $table->unique(['user_id', 'trip_id'])->comment('ضمان تقييم المستخدم للرحلة مرة واحدة فقط');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
