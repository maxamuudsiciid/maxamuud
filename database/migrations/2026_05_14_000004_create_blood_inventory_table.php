<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('blood_group', 5)->unique();
            $table->integer('quantity'); // total quantity in stock in ml
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_inventory');
    }
};
