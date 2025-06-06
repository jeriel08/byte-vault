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
        Schema::table('stock_out_details', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable()->useCurrentOnUpdate()->after('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_out_details', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }
};
