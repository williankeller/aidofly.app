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
        Schema::create('voices', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('provider', 16);
            $table->string('model', 36);
            $table->boolean('status')->default(1)->comment('0: Disabled, 1: Enabled');
            $table->string('sample');
            $table->string('token', 64)->nullable();
            $table->string('name', 32);
            $table->tinyInteger('gender')->nullable()->comment('1: Male, 2: Female');
            $table->string('case', 32);
            $table->string('accent', 16)->nullable();
            $table->string('age', 16)->nullable();
            $table->string('tone', 16)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voices');
    }
};
