<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blood_collections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('donor_id')->constrained()->onDelete('cascade');
            $table->string('blood_group', 5);
            $table->integer('quantity'); // in ml
            $table->date('donation_date');
            $table->date('expiry_date');
            $table->enum('screening_result', ['Pending', 'Safe', 'Unsafe'])->default('Pending');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('blood_collections');
    }
};
