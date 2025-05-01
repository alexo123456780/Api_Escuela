<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Entrega extends Model
{

    use HasFactory,HasApiTokens,Notifiable;

    protected $fillable = ['alumno_id','tarea_id','archivos','calificacion','comentarios_profesor','fecha_entregada'];

    public function estudiante(){

        return $this->belongsTo(Estudiante::class,'alumno_id');

    }

    public function tarea(){

        return $this->belongsTo(Tarea::class,'tarea_id');

    }



    
}
