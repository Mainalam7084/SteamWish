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
        Schema::create('games', function(Blueprint $table){
            $table->unsignedInteger('appid')->primary();
            $table->mediumText('name');
            $table->timestamp('last_modified');
            $table->unsignedInteger('price_change_number'); // Número que sube cada vez que se actualiza el precio del juego
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
