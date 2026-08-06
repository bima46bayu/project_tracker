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
        Schema::table('indirect_costs', function (Blueprint $table) {
            $table->integer('qty')->default(1)->after('master_indirect_cost_id');
            $table->decimal('harga_satuan', 15, 2)->default(0)->after('qty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('indirect_costs', function (Blueprint $table) {
            $table->dropColumn(['qty', 'harga_satuan']);
        });
    }
};
