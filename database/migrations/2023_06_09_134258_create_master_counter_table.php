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
        Schema::create('master_counter', function (Blueprint $table) {
            $table->id();
            $table->string('prefix',100);
            $table->integer('urut');
            $table->date('tanggal');
            $table->string('keterangan',10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_counter');
    }
};
