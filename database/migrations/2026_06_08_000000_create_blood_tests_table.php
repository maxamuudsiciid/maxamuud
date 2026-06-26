<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blood_collection_id')->constrained()->onDelete('cascade');
            $table->date('test_date');
            $table->enum('hiv_result', ['Negative', 'Positive']);
            $table->enum('hbv_result', ['Negative', 'Positive']);
            $table->enum('hcv_result', ['Negative', 'Positive']);
            $table->enum('syphilis_result', ['Negative', 'Positive']);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_tests');
    }
};
