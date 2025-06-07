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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id()->comment('المعرف الفريد للإشعار');
            $table->foreignId('user_id')->constrained()->onDelete('cascade')->comment('المستخدم المستهدف بالإشعار');
            $table->string('title')->comment('عنوان الإشعار');
            $table->text('message')->comment('محتوى الإشعار');
            $table->boolean('is_read')->default(false)->comment('حالة قراءة الإشعار');
            $table->string('type')->comment('نوع الإشعار (حجز، دفع، شكوى، إلخ)');
            $table->json('data')->nullable()->comment('بيانات إضافية للإشعار (تخزن كJSON)');
            $table->timestamps();
            $table->softDeletes(); // Soft deletes

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifiations');
    }
};
