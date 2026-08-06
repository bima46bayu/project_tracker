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
            $table->decimal('modal_satuan', 15, 2)->default(0)->after('harga_satuan');
            $table->decimal('total_modal', 15, 2)->default(0)->after('total_harga');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('task_items', function (Blueprint $table) {
            $table->dropColumn(['modal_satuan', 'total_modal']);
        });
    }
};
