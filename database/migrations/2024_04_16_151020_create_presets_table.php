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
            $table->enum('source', ['user', 'system'])->default('user');
            $table->enum('visibility', ['public', 'workspace', 'private'])->default('private');
            $table->boolean('status')->default(1)->comment('0: Disabled, 1: Enabled');
            $table->string('title', 128);
            $table->text('description');
            $table->longText('template');
            $table->string('icon', 32)->nullable();
            $table->string('color', 7)->nullable();

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('category_id')->nullable()->constrained('categories')->onDelete('set null');

            $table->unsignedBigInteger('usage_count')->default(0);

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
