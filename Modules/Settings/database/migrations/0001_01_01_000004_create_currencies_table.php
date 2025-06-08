<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration 
{
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->char('currency', 30);
            $table->char('symbol', 4);
            $table->enum('display', ['symbol', 'name'])->default('symbol');
            $table->boolean('is_default')->default(false);
            $table->double('exchange_rate');
            $table->timestamps();
            $table->softDeletes(); // Soft deletes
        });
    }

    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}