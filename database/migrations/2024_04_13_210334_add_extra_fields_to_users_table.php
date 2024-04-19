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
            $table->string('firstname', 32)->after('email');
            $table->string('lastname', 32)->after('firstname');
            $table->boolean('role')->default(0)->comment('0: User, 1: Admin');
            $table->boolean('status')->default(1)->comment('0: Disabled, 1: Enabled');
            $table->text('configuration')->nullable();

            $table->dropColumn('name');
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
