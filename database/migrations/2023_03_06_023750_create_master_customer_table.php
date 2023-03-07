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
        Schema::create('master_customer', function (Blueprint $table) {
            $table->id('id_customer');
            $table->uuid('uuid');
            $table->string('name_customer',100);
            $table->string('email_customer',50);
            $table->string('phone_customer',50);
            $table->integer('gender_customer');
            $table->string('password',100);
            $table->string('image',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_customer');
    }
};
