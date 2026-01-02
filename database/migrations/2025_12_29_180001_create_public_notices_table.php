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
        Schema::create('public_notices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained();
            $table->string('publication_organ');
            $table->unsignedInteger('days_between_email_and_notice')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('public_notices');
    }
};
