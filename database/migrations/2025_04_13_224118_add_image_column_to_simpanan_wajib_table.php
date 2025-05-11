<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('simpanan_wajib', function (Blueprint $table) {
        $table->string('image')->nullable(); // Ganti 'column_name' dengan nama kolom terakhir atau kolom tertentu.
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('image');
    });
}

};
