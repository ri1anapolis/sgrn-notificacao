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
        Schema::create('digital_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('notification_id')->constrained();
            $table->foreignId('notified_person_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->date('contact_date');
            $table->time('contact_time');
            $table->string('whatsapp_result')->nullable();
            $table->string('email_result')->nullable();
            $table->text('custom_result')->nullable();
            $table->timestamps();

            $table->unique(['notification_id', 'notified_person_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_contacts');
    }
};
