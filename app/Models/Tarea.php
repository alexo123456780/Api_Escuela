<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Tarea extends Model
{

    use HasFactory,HasApiTokens,Notifiable;

    protected $fillable = ['titulo_tarea','instrucciones','fecha_vencimiento','status','curso_id','maestro_id'];


    public function curso(){

        return $this->belongsTo(Curso::class,'curso_id');

    }

    public function entregas(){

        return $this->hasMany(Entrega::class,'tarea_id');

    }

    public function maestro(){

        return $this->belongsTo(Maestro::class,'maestro_id');


    }



    
}
