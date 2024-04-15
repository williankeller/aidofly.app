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
        Schema::create('library', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('object', 32); // (document, code, audio, image.)
            $table->enum('visibility', ['public', 'private'])->default('public');
            $table->string('model');
            $table->float('cost');
            $table->text('params');
            $table->string('title', 128);
            $table->text('content');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('library');
    }
};
