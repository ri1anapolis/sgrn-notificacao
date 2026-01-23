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
        Schema::table('diligence_results', function (Blueprint $table) {
            $table->unsignedInteger('order')->default(0)->after('description');
            $table->boolean('active')->default(true)->after('order');
            $table->boolean('is_custom')->default(false)->after('active');
            $table->text('original_description')->nullable()->after('description');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diligence_results', function (Blueprint $table) {
            $table->dropColumn(['order', 'active', 'is_custom', 'original_description']);
        });
    }
};
