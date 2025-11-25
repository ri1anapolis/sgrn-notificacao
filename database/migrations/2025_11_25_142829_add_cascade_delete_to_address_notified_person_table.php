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
        Schema::table('address_notified_person', function (Blueprint $table) {
            $table->dropForeign(['notified_person_id']);

            $table->foreignId('notified_person_id')
                ->change()
                ->constrained()
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('address_notified_person', function (Blueprint $table) {
            $table->dropForeign(['notified_person_id']);

            $table->foreignId('notified_person_id')
                ->change()
                ->constrained();
        });
    }
};
