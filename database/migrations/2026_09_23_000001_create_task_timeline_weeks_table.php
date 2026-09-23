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
        Schema::create('task_timeline_weeks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_task_id')->constrained('project_tasks')->cascadeOnDelete();
            $table->enum('type', ['plan', 'realisasi']);
            $table->integer('week_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('progress_percentage', 5, 2)->default(0);
            $table->boolean('is_extra')->default(false);
            $table->timestamps();

            $table->unique(['project_task_id', 'type', 'week_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_timeline_weeks');
    }
};
