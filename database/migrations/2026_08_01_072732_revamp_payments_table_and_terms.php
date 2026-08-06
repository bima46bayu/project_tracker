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
        Schema::table('projects', function (Blueprint $table) {
            $table->string('payment_term')->nullable()->after('lokasi');
        });

        Schema::table('project_subkon', function (Blueprint $table) {
            $table->string('payment_term')->nullable()->after('subkon_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['amount', 'description', 'date']);
            
            $table->string('invoice')->nullable()->after('type');
            $table->string('keterangan')->nullable()->after('invoice');
            $table->decimal('nilai', 15, 2)->default(0)->after('keterangan');
            $table->date('tanggal')->nullable()->after('nilai');
            $table->date('tanggal_payment')->nullable()->after('tanggal');
            $table->decimal('nilai_payment', 15, 2)->default(0)->after('tanggal_payment');
            $table->foreignId('subkon_id')->nullable()->after('nilai_payment')->constrained('subkons')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table->dropColumn('payment_term');
        });

        Schema::table('project_subkon', function (Blueprint $table) {
            $table->dropColumn('payment_term');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['subkon_id']);
            $table->dropColumn(['invoice', 'keterangan', 'nilai', 'tanggal', 'tanggal_payment', 'nilai_payment', 'subkon_id']);
            
            $table->decimal('amount', 15, 2)->default(0);
            $table->text('description')->nullable();
            $table->date('date')->nullable();
        });
    }
};
