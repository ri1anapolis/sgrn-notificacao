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
        Schema::table('alienation_real_estates', function (Blueprint $table) {
            $table->string('total_amount_debt')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alienation_real_estates', function (Blueprint $table) {
            $table->bigInteger('total_amount_debt')->nullable()->change();
        });
    }
};
