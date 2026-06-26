<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('donors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->enum('gender', ['Male', 'Female',]);
            $table->date('date_of_birth');
            $table->string('blood_group', 5);
            $table->string('phone');
            $table->string('email')->unique();
            $table->text('address');
            $table->date('last_donation_date')->nullable();
            $table->enum('status', ['Active', 'Inactive', 'Deferred'])->default('Active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('donors');
    }
};
