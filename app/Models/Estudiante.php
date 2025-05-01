<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


use Illuminate\Foundation\Auth\User as Authenticatable;

class Estudiante extends Authenticatable
{
    use HasFactory,Notifiable,HasApiTokens;

    protected $fillable = ['nombre_estudiante','apellido','matricula','password','perfil_estudiante'];


    public function entregas(){

        return $this->hasMany(Entrega::class,'alumno_id','tarea_id');


    }

    public function calificacion(){

        return $this->hasMany(Calificacion::class,'estudiante_id');

    }



  public function rubrica1(){

    return $this->hasOne(RubricaP1::class,'estudiante_id');

  }


  
  public function rubrica2(){

    return $this->hasOne(RubricaP2::class,'estudiante_id');

  }

  
  public function rubrica3(){

    return $this->hasOne(RubricaP3::class,'estudiante_id');

  }



  




    




    
}
