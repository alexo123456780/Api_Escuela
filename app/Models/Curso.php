<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Curso extends Model
{

    use HasFactory,Notifiable;

    protected $fillable = ['nombre_curso','descripcion_curso','imagen_curso'];


    public function maestro(){

        return $this->hasOne(Maestro::class,'curso_id');

    }

    public function tareas(){

        return $this->hasMany(Tarea::class,'curso_id');
        
    }


}
