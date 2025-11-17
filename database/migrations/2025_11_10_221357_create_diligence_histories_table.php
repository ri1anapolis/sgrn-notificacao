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
        Schema::create('diligence_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diligence_id')->constrained('diligences');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('old_diligence_result_id')->nullable()->constrained('diligence_results');
            $table->foreignId('new_diligence_result_id')->nullable()->constrained('diligence_results');
            $table->text('old_observations')->nullable();
            $table->text('new_observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diligence_histories');
    }
};
