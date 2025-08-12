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
        Schema::table('certifications', function (Blueprint $table) {
            // initial_amount
            $table->decimal('initial_amount', 10, 2)->nullable()->default(200000.00)->comment('Initial Amount')->after('amount');
            // targets
            $table->text('targets')->nullable()->after('benefits');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('certifications', function (Blueprint $table) {
            $table->dropColumn('initial_amount');
            $table->dropColumn('targets');
        });
    }
};
