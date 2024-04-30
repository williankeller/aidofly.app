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
        Schema::create('libraries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->enum('type', ['writer', 'voiceover', 'image', 'transcript']);
            $table->enum('visibility', ['public', 'private', 'workspace'])->default('private');

            $table->string('title', 128);
            $table->longText('params');
            $table->longText('content');

            $table->string('model', 64);
            $table->decimal('cost', 12, 11)->nullable();
            $table->integer('tokens')->nullable()->default(0);
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->unsignedBigInteger('resource_id')->index()->nullable();

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
