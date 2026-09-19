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
        Schema::table('actual_indirect_costs', function (Blueprint $table) {
            $table->date('tanggal')->nullable()->after('indirect_cost_id');
            $table->integer('qty')->default(1)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actual_indirect_costs', function (Blueprint $table) {
            $table->dropColumn('tanggal');
            $table->decimal('qty', 10, 2)->default(1)->change();
        });
    }
};
