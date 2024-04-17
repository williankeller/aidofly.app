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
        Schema::create('presets', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('type', 32);
            $table->boolean('status');
            $table->string('title', 128);
            $table->text('description');
            $table->longText('template');
            $table->string('icon', 32);
            $table->string('color', 7);
            $table->foreignId('category_id')->constrained('categories')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('presets');
    }
};
