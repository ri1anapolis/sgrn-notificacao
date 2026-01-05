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
        Schema::create('public_notice_publications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('public_notice_id')->constrained();
            $table->unsignedTinyInteger('publication_order');
            $table->string('edition')->nullable();
            $table->string('notice_number')->nullable();
            $table->date('publication_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_notice_publications');
    }
};
