<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class RubricaP2 extends Model
{
    use HasFactory,HasApiTokens,Notifiable;

    protected $fillable = ['maestro_id','alumno_id','calificacion_id','calificacion_tareas','calificacion_examen','asistencia','calificacion_final'];


    public function maestro(){

        return $this->belongsTo(Maestro::class,'maestro_id');

    }


    public function estudiante(){

        return $this->belongsTo(Estudiante::class,'alumno_id');

    }

    public function calificacion(){

        return $this->belongsTo(Calificacion::class,'calificacion_id');

    }







    
}   
