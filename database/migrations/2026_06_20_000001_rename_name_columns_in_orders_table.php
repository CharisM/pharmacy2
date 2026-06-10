<?php

use Illuminate\Database\Migrations\Migration;

// This migration is intentionally a no-op.
// The orders table was updated to use full_name directly
// in the create migration (2026_06_10_034817).
return new class extends Migration
{
    public function up(): void {}
    public function down(): void {}
};
