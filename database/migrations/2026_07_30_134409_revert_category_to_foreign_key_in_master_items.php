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
        Schema::table('master_items', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->foreignId('master_category_id')->nullable()->after('satuan')->constrained('master_categories')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_items', function (Blueprint $table) {
            $table->dropForeign(['master_category_id']);
            $table->dropColumn('master_category_id');
            $table->string('category')->default('Material')->after('satuan');
        });
    }
};
