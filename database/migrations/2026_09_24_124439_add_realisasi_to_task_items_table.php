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
        Schema::table('task_items', function (Blueprint $table) {
            $table->decimal('qty_realisasi', 10, 2)->default(0)->after('total_modal')->comment('Realisasi quantity');
            $table->decimal('harga_satuan_realisasi', 15, 2)->default(0)->after('qty_realisasi')->comment('Realisasi unit price');
            $table->decimal('modal_satuan_realisasi', 15, 2)->default(0)->after('harga_satuan_realisasi')->comment('Realisasi modal per unit');
            $table->decimal('total_harga_realisasi', 15, 2)->default(0)->after('modal_satuan_realisasi')->comment('Realisasi total price');
            $table->decimal('total_modal_realisasi', 15, 2)->default(0)->after('total_harga_realisasi')->comment('Realisasi total modal');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_items', function (Blueprint $table) {
            $table->dropColumn([
                'qty_realisasi',
                'harga_satuan_realisasi',
                'modal_satuan_realisasi',
                'total_harga_realisasi',
                'total_modal_realisasi',
            ]);
        });
    }
};
