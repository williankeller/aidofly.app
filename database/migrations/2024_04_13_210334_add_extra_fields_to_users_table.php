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
        Schema::table('users', function (Blueprint $table) {
            $table->string('firstname')->after('email');
            $table->string('lastname')->after('firstname');
            $table->dropColumn('name');
            $table->unsignedSmallInteger('role')->default(0);  // 0 = user, 1 = admin
            $table->unsignedSmallInteger('status')->default(1);  // 0 = disabled, 1 = enabled
            $table->text('configuration')->nullable(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['firstname', 'lastname', 'role', 'status', 'configuration']);
            $table->string('name')->after('email');
        });
    }
};
