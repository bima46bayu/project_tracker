<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('master_indirect_costs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('satuan')->nullable();
            $table->foreignId('master_category_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('master_indirect_costs');
    }
};
