<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Calificacion extends Model
{

    use HasFactory,HasApiTokens,Notifiable;

    protected $fillable = ['maestro_id','estudiante_id','parcial_1','parcial_2','parcial_3','promedio_final'];


    public function estudiante(){

        return $this->belongsTo(Estudiante::class,'estudiante_id');

    }

    public function maestro(){

        return $this->belongsTo(Maestro::class,'maestro_id');

    }


    public function rubricap1(){

        return $this->hasOne(RubricaP1::class,'calificacion_id');

    }

    public function rubricap2(){

        return $this->hasOne(RubricaP2::class,'calificacion_id');


    }

    public function rubricap3(){

        return $this->hasOne(RubricaP3::class,'calificacion_id');

    }

    








    
}
