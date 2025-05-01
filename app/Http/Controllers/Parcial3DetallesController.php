<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Maestro;
use App\Models\RubricaP1;
use App\Models\RubricaP2;
use App\Models\RubricaP3;
use Illuminate\Http\Request;

class Parcial3DetallesController extends Controller
{


    //terminar esta parte crack

    public function subirCalificacionP3(Request $request, $id_maestro){

        try{

            $datosValidados = $request->validate(
                [

                'alumno_id' => 'required|numeric|exists:estudiantes,id',
                'calificacion_id' => 'required|numeric|exists:calificacions,id',
                'calificacion_tareas' => 'required|numeric|min:1|max:10',
                'calificacion_examen' => 'required|numeric|min:1|max:10',
                'asistencia' => 'required|numeric|min:1|max:10'
                ],

                [
                    'calificacion_tareas.min' => 'El rango de calificacion de tareas es del 1 al 10',
                    'calificacion_tareas.max' => 'El rango de calificacion de tareas es del 1 al 10',
                    'calificacion_examen.min' => 'El rango minimo de la calificacion del examen es de 1 a 10',
                    'calificacion.max' => 'El rango maximo de la calificacion del examen es de 1 a 10',
                    'asistencia.min' => 'El rango de la asistencua es de 1 a 10',
                    'asistencia.max' => 'El rango de asistencia es de 1 al 10'
                ]
        );

        $buscarMaestro = Maestro::find($id_maestro);

        if(!$buscarMaestro){

            return response()->json([
                'status' => false,
                'message' => 'No existe informacion del maestro o no se ha registrado aun',
                'code' => 400

            ],400);

        }


        $datosRepetidos = RubricaP3::where('alumno_id',$datosValidados['alumno_id'])->where('calificacion_id',$datosValidados['calificacion_id'])->where('maestro_id',$buscarMaestro['id'])->exists();

        if($datosRepetidos){

            return response()->json([

                'status' => false,
                'message' => 'Ya hay una calificacion asignada para este estudiante',
                'code' => 400

            ],400);

        }


        $calificacion_tareas = $datosValidados['calificacion_tareas'];
        $calificacion_examen = $datosValidados['calificacion_examen'];
        $calificacion_asistencia = $datosValidados['asistencia'];


        $calcularPromedioTareas = $calificacion_tareas * 7;
        $calcularPromedioExamen = $calificacion_examen * 3;

        $promedioTareas = $calcularPromedioTareas / 10;
        $promedioExamen = $calcularPromedioExamen / 10;

        $calificacion_final = $promedioTareas + $promedioExamen;



        $calificacion_FinalP1 = RubricaP1::where('estudiante_id',$datosValidados['alumno_id'])->where('calificacion_id',$datosValidados['calificacion_id'])->first();
        $calificacion_FinalP2 = RubricaP2::where('alumno_id',$datosValidados['alumno_id'])->where('calificacion_id',$datosValidados['calificacion_id'])->first();
        $buscarBoleta = Calificacion::where('estudiante_id',$datosValidados['alumno_id'])->where('maestro_id',$buscarMaestro['id'])->first();

        $calificacion1 = $calificacion_FinalP1->calificacion_final;
        $calificacion2 = $calificacion_FinalP2->calificacion_final;


        $promedio_final3parciales = $calificacion1 + $calificacion2+ $calificacion_final;
        $resultadoFinal = $promedio_final3parciales / 3 ;


        $datosCalificacionP3 = RubricaP3::create([

            'maestro_id' => $buscarMaestro['id'],
            'alumno_id' => $datosValidados['alumno_id'],
            'calificacion_id' => $datosValidados['calificacion_id'],
            'calificacion_tareas' => $datosValidados['calificacion_tareas'],
            'calificacion_examen' => $datosValidados['calificacion_examen'],
            'asistencia' => $calificacion_asistencia,
            'calificacion_final' => $calificacion_final,

        ]);

        $buscarBoleta->update([

            'parcial_3' => $calificacion_final,
            'promedio_final' => $resultadoFinal

        ]);

        $buscarBoleta->save();

        return response()->json([

            'status' => 'true',
            'message' => 'Calificacion asignada correctamente y calculo del promedio final correcto',
            'data' => $datosCalificacionP3,
            'dataBoleta' => $buscarBoleta,
            'code' => 200

        ],200);
        

        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validacion en los campos solicitados',
                'warning' => $e->errors(),
                'code' => 400,
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
