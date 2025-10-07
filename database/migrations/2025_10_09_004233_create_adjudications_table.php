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
        Schema::create('adjudications', function (Blueprint $table) {
            $table->id();
            $table->integer('office')->nullable();
            $table->string('adjudicated_property_registration')->nullable();
            $table->string('adjudicated_property_identification')->nullable();
            $table->string('adjudicated_property_registry_office')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adjudications');
    }
};
