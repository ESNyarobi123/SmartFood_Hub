<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the enum to include 'pending' status (keeping default as 'active' for backward compatibility)
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('pending', 'active', 'paused', 'cancelled', 'expired') DEFAULT 'active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values (remove 'pending')
        DB::statement("ALTER TABLE subscriptions MODIFY COLUMN status ENUM('active', 'paused', 'cancelled', 'expired') DEFAULT 'active'");
    }
};
