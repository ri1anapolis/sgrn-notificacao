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
        Schema::create('alienation_movable_properties', function (Blueprint $table) {
            $table->id();
            $table->string('creditor');
            $table->integer('office')->nullable();
            $table->text('guarantee_movable_property_description')->nullable();
            $table->string('contract_registry_data')->nullable();
            $table->string('emoluments_intimation')->nullable();
            $table->string('contract_number')->nullable();
            $table->date('contract_date')->nullable();
            $table->bigInteger('total_amount_debt')->nullable();
            $table->date('debt_position_date')->nullable();
            $table->string('default_period')->nullable();
            $table->boolean('grace_period')->default(false);
            $table->text('contractual_clause')->nullable();
            $table->string('contract_registry_office')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alienation_movable_properties');
    }
};
