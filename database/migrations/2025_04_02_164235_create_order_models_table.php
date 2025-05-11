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
        Schema::create('order_models', function (Blueprint $table) {
            $table->id();
            $table->string('angota_id');
            $table->string('amount');
            $table->string('simpanan_id');
            $table->string('status');
            $table->string('jenis');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_models');
    }
};
