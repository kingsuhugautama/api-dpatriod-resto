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
        Schema::create('trans_order_detail', function (Blueprint $table) {
            $table->id('id_order_detail');
            $table->integer('id_order')->constrained('trans_order');
            $table->integer('id_menu')->constrained('master_menu');
            $table->double('qty',12,2);
            $table->double('total_price',12,2);
            $table->string('note',200);
            $table->integer('status');
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_order_detail');
    }
};
