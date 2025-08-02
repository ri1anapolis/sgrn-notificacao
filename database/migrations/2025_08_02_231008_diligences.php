<?php

use App\Enums\DiligenceResult;
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
        Schema::create('diligences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('address_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->tinyInteger('visit_number')->unsigned();
            $table->string('diligence_result')->default(DiligenceResult::NotFound->value);
            $table->text('observations')->nullable();
            $table->dateTime('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diligences');
    }
};
