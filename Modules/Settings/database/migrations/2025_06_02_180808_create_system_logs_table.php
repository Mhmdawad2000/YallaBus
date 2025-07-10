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
        Schema::create('system_logs', function (Blueprint $table) {
            $table->id();
            $table->string('model_type', 100); // اسم الموديل (مثلاً: App\Models\User)
            $table->unsignedBigInteger('model_id'); // أي دي الموديل
            $table->string('action'); // create, update, delete, restore
            $table->text('notification_title')->nullable();
            $table->text('notification_body')->nullable();
            $table->text('comments')->nullable();
            $table->json('old_data')->nullable(); // البيانات القديمة
            $table->json('new_data')->nullable(); // البيانات الجديدة
            $table->integer('user_id')->nullable(); // اليوزر الذي قام بالعملية
            $table->string('ip_address')->nullable(); // عنوان IP
            $table->string('user_agent')->nullable(); // متصفح المستخدم
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->index(['model_type', 'model_id']);
            $table->index('user_id');
            $table->index('created_at');
            $table->engine = 'Aria';

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_logs');
    }
};
