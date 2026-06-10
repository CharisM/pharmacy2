<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable()->after('user_id');
        });

        DB::statement("UPDATE orders SET customer_name = first_name || ' ' || last_name WHERE customer_name IS NULL");

        Schema::table('orders', function (Blueprint $table) {
            $table->string('customer_name')->nullable(false)->change();
            $table->dropColumn(['first_name', 'last_name']);
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('first_name')->nullable()->after('user_id');
            $table->string('last_name')->nullable()->after('first_name');
        });

        DB::statement("UPDATE orders SET first_name = customer_name, last_name = ''");

        Schema::table('orders', function (Blueprint $table) {
            $table->string('first_name')->nullable(false)->change();
            $table->dropColumn('customer_name');
        });
    }
};
