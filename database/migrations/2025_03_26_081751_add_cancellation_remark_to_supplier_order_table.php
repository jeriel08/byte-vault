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
        Schema::table('supplier_orders', function (Blueprint $table) {
            //
            $table->text('cancellationRemark')->nullable()->after('cancelledDate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('supplier_orders', function (Blueprint $table) {
            //
            $table->dropColumn('cancellationRemark');
        });
    }
};
