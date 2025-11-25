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
        Schema::create('others', function (Blueprint $table) {
            $table->id();
            $table->string('creditor');
            $table->integer('office')->nullable();
            $table->string('guarantee_property_registration')->nullable();
            $table->string('guarantee_property_address')->nullable();
            $table->string('contract_registration_act')->nullable();
            $table->string('emoluments_intimation')->nullable();
            $table->string('contract_number')->nullable();
            $table->date('contract_date')->nullable();
            $table->bigInteger('total_amount_debt')->nullable();
            $table->date('debt_position_date')->nullable();
            $table->string('default_period')->nullable();
            $table->boolean('grace_period')->default(false);
            $table->text('contractual_clause')->nullable();
            $table->string('real_estate_registry_location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('others');
    }
};
