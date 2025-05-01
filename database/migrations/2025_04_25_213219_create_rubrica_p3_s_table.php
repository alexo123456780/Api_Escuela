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
        Schema::create('rubrica_p3_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('maestro_id')->constrained('maestros')->onDelete('cascade');
            $table->foreignId('alumno_id')->constrained('estudiantes')->onDelete('cascade');
            $table->foreignId('calificacion_id')->constrained('calificacions')->onDelete('cascade');
            $table->integer('calificacion_tareas')->nullable();
            $table->integer('calificacion_examen')->nullable();
            $table->integer('asistencia')->nullable();
            $table->integer('calificacion_final')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rubrica_p3_s');
    }
};
