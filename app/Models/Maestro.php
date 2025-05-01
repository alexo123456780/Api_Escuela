<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

use Illuminate\Foundation\Auth\User as  Authenticatable;
use Laravel\Sanctum\HasApiTokens;



class Maestro extends Authenticatable
{

    use HasFactory,Notifiable,HasApiTokens;

    protected $fillable = ['nombre_maestro','apellido','matricula','numero_telefono','password','perfil_maestro','curso_id'];


    public function cursos(){

        return $this->belongsTo(Curso::class,'curso_id');


    }

    public function calificaciones(){

        return $this->hasMany(Maestro::class,'maestro_id');

    }

    public function tareas(){

        return $this->hasMany(Tarea::class,'maestro_id');


    }


    public function rubricap1(){

        return $this->hasMany(RubricaP1::class,'maestro_id');

    }

    public function rubricap2(){

        return $this->hasMany(RubricaP2::class,'maestro_id');

    }


    public function rubricap3(){

        return $this->hasMany(RubricaP3::class,'maestro_id');

    }

    






    
}
