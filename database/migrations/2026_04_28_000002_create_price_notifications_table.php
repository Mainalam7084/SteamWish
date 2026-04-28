<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('appid');
            $table->string('game_name');
            $table->string('game_image')->nullable();
            // Precios en céntimos
            $table->unsignedInteger('old_price')->default(0); // precio base antes
            $table->unsignedInteger('new_price')->default(0); // precio actual
            $table->unsignedTinyInteger('discount_percent')->default(0);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_notifications');
    }
};
