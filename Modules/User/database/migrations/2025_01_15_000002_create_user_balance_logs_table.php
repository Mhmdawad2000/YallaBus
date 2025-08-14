<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('user_balance_log', function (Blueprint $table) {
            $table->id(); // Unique identifier for the log entry
            $table->unsignedBigInteger('user_id')->nullable(); // Reference to the user
            // $table->unsignedBigInteger('currency_id')->default(1); // You can uncomment if currency_id is needed
            $table->unsignedBigInteger('booking')->nullable();

            $table->decimal('old_balance', 10, 2); // Old balance before the change
            $table->decimal('new_balance', 10, 2); // New balance after the change
            $table->string('reason')->comment('Reason for the balance change'); // Reason for the balance change
            $table->timestamps(); //timestamps
            $table->softDeletes(); // Soft deletes for the log entries

            // Relationships
            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('booking_id')->references('id')->on('bookings')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_balance_log'); // Drop the user_balance_log table if rolling back the migration
    }
};
