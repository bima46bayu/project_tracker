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
        Schema::table('project_documentations', function (Blueprint $table) {
            $table->string('category')->default('PROGRESS')->after('project_id');
            $table->string('document_number')->nullable()->after('title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_documentations', function (Blueprint $table) {
            $table->dropColumn(['category', 'document_number']);
        });
    }
};
