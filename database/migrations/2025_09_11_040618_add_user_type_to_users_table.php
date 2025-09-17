<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    # Up method, what happens running the migration
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            # Modifies the existing users table (not creating a new one)
            # Adds an enum column called user_type
            # Only allows two values: 'organiser' or 'attendee'
            # Sets default value to 'attendee' for new users
            $table->enum('user_type', ['organiser', 'attendee'])->default('attendee');
        });
    }

    /**
     * Reverse the migrations.
     */
    # Down method, if migration is rolled back
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            # drop the user type column
            $table->dropColumn('user_type');
        });
    }
};
