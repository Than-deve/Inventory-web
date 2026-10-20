<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->string('name', 255);
            $table->string('code', 50)->unique();
            $table->integer('stock');
            $table->enum('condition', ['baik', 'rusak ringan', 'rusak berat']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};