<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('games', function (Blueprint $table) {
            // Solo añadir si no existen ya
            if (!\Illuminate\Support\Facades\Schema::hasColumn('games', 'price')) {
                $table->unsignedInteger('price')->default(0);
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('games', 'base_price')) {
                $table->unsignedInteger('base_price')->default(0);
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('games', 'discount_percent')) {
                $table->unsignedTinyInteger('discount_percent')->default(0);
            }
            if (!\Illuminate\Support\Facades\Schema::hasColumn('games', 'image')) {
                $table->string('image')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('games', function (Blueprint $table) {
            $table->dropColumn(['price', 'base_price', 'discount_percent', 'image']);
        });
    }
};
