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
        Schema::create('task_item_realisasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_item_id')->constrained('task_items')->onDelete('cascade');
            $table->foreignId('task_timeline_week_id')->constrained('task_timeline_weeks')->onDelete('cascade');
            $table->decimal('qty_realisasi', 10, 2)->default(0);
            $table->decimal('harga_satuan_realisasi', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_item_realisasis');
    }
};
