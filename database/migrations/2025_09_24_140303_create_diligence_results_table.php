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
        Schema::create('diligence_results', function (Blueprint $table) {
            $table->id();
            $table->string('group');
            $table->string('code')->unique();
            $table->string('description');
            $table->timestamps();
        });

        Schema::table('diligences', function (Blueprint $table) {
            $table->dropColumn('diligence_result');
            $table->foreignId('diligence_result_id')->nullable()->constrained('diligence_results');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('diligences', function (Blueprint $table) {
            $table->dropConstrainedForeignId('diligence_result_id');
            $table->string('diligence_result')->nullable();
        });

        Schema::dropIfExists('diligence_results');
    }
};
