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
        Schema::create('trans_order', function (Blueprint $table) {
            $table->id('id_order');
            $table->uuid('uuid');
            $table->integer('id_customer');
            $table->double('total_qty',12,2);
            $table->double('total_price',12,2);
            $table->string('name_user',100);
            $table->integer('id_type_payment')->constrained('master_type_payment');
            $table->double('price_user',12,2);
            $table->double('return_price_user',12,2);
            $table->double('discount',12,2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_order');
    }
};
