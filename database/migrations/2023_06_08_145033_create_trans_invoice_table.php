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
        Schema::create('trans_invoice', function (Blueprint $table) {
            $table->id('id_invoice');
            $table->uuid('uuid');
            $table->string('referenceNo',100);
            $table->string('tXid',100);
            $table->string('payMethod',3);
            $table->integer('status')->default(0);
            $table->json('body');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_invoice');
    }
};
