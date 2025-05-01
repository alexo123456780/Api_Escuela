<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Maestro;
use App\Models\RubricaP1;
use Illuminate\Http\Request;

class Parcial1DetallesController extends Controller
{


    public function calificarParcial1(Request $request , $id_maestro){

        try{

            $datosValidados = $request->validate([

                'estudiante_id' => 'required|numeric|exists:estudiantes,id',
                'calificacion_id' => 'required|numeric|exists:calificacions,id',
                'calificacion_tareas' => 'required|numeric|min:1|max:10',
                'calificacion_examen' => 'required|numeric|min:1|max:10',
                'asistencia' => 'required|numeric|min:1|max:10'

            ],

            [
                'calificacion_tareas.min' => 'La calificacion debe ser del 1 al 10',
                'calificacion_tareas.max' => 'La calificacion debe ser del 1 al 10',

            ],

            [
                'calificacion_examen.min' => 'La calificacion del examen debe ser del 1 al 10',
                'calificacion_examen.max' =>  'La calificacion del examen debe ser del 1 al 10'

            ],

            [
                'asistencia.min' => 'La asistencia debe ser del 1 al 10',
                'asistencia.max' => 'La asistencia debe ser del 1 al 10'

            ]
        );


            $buscarMaestro = Maestro::find($id_maestro);

            if(!$buscarMaestro){

                return response()->json([

                    'status' => false,
                    'message' => 'El maestro no existe o no se ha registrado aun',
                    'code' => 404


                ]);

            }

            $datosRepetidos = RubricaP1::where('estudiante_id',$datosValidados['estudiante_id'])->where('calificacion_id',$datosValidados['calificacion_id'])->where('maestro_id',$buscarMaestro['id'])->exists();

            if($datosRepetidos){

                return response()->json([

                    'status' => false,
                    'message' => 'Lo sentimos no puedes asignar una calificacion a un estudiante que ya esta asignado a una calificacion',
                    'code' => 400

                ],400);

            }

            $calificacionTareas = $datosValidados['calificacion_tareas'];
            $calificacion_examen = $datosValidados['calificacion_examen'];
            $asistencia = $datosValidados['asistencia'];


            $calcularPromedioTarea = $calificacionTareas * 7;
            $calcularPromedioExamen = $calificacion_examen * 3;

            $promedioTarea = $calcularPromedioTarea / 10;
            $promedioExamen = $calcularPromedioExamen / 10;


            $promedioFinal  = $promedioTarea + $promedioExamen;


            $asignarCalificacion = RubricaP1::create([

                'maestro_id' => $buscarMaestro['id'],
                'estudiante_id' => $datosValidados['estudiante_id'],
                'calificacion_id' => $datosValidados['calificacion_id'],
                'calificacion_tareas' => $calificacionTareas,
                'calificacion_examen' => $calificacion_examen,
                'asistencia' => $asistencia,
                'calificacion_final' => $promedioFinal

            ]);


            //hacer update para actulizar las 3 calificaciones de los 3 parciales

            $calificacionRubrica = Calificacion::where('estudiante_id',$datosValidados['estudiante_id'])->where('maestro_id',$buscarMaestro['id'])->first();

            $calificacionRubrica->update([

                'parcial_1' => $promedioFinal,

            ]);


            return response()->json([

                'status' => true,
                'message' => 'Calificacion final asignada al alumno correctamente',
                'data' => $asignarCalificacion,
                'code' => 200
            ],200);



        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Ocurrio un error de validacion en el campo solicitado',
                'warning' => $e->errors(),
                'code' => 400

            ],400);
        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de codificacion',
                'warning' => $e->getMessage(),
                'code' => 500

            ],500);


        }


    }




    
}
