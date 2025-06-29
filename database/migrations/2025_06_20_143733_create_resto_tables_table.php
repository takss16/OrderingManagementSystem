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
        Schema::create('resto_tables', function (Blueprint $table) {
            $table->id();
            $table->string('name');            // e.g., "VIP Table", "Window Side"
            $table->integer('number')->unique(); // Actual table number (e.g., 1, 2, 3)
            $table->string('qr_code_path')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resto_tables');
    }
};
