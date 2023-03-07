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
        Schema::create('master_employe', function (Blueprint $table) {
            $table->id('id_employe');
            $table->string('name',100);
            $table->integer('id_position')->constrained('master_position');;
            $table->integer('gender');
            $table->string('image',100);
            $table->string('email',100);
            $table->string('phone',100);
            $table->string('password',100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_employe');
    }
};
