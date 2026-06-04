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
        Schema::create('diagnostics', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->datetime('date');
            $table->foreignId("patient_id")->constrained("patients")->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained("doctors")->onDelete('cascade');
            $table->string("severity");
            $table->string("recommendations");
            $table->string("type_diagnosis");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diagnostics');
    }
};
