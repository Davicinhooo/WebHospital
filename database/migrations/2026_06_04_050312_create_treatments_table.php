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
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string("duration");
            $table->foreignId("diagnostic_id")->constrained("diagnostics")->onDelete("cascade");
            $table->foreignId('doctor_id')->constrained("doctors")->onDelete('cascade');
            $table->string("status");
            $table->string("administration_frequency");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatments');
    }
};
