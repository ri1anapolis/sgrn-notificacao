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
        Schema::create('adverse_possessions', function (Blueprint $table) {
            $table->id();
            $table->integer('office')->nullable();
            $table->string('adverse_possession_property_registration');
            $table->string('adverse_possession_property_identification');
            $table->string('adverse_possession_property_registry_office');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adverse_possessions');
    }
};
