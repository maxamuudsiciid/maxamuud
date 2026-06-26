<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hospital_id')->constrained()->onDelete('cascade');
            $table->string('patient_name');
            $table->string('blood_group', 5);
            $table->integer('quantity'); // in ml
            $table->date('request_date');
            $table->enum('status', ['Pending', 'Approved', 'Rejected', 'Fulfilled'])->default('Pending');
            $table->enum('urgency_level', ['Normal', 'Urgent', 'Emergency'])->default('Normal');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blood_requests');
    }
};
