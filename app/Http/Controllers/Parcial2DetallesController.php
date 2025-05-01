<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Maestro;
use App\Models\RubricaP2;
use Illuminate\Http\Request;

class Parcial2DetallesController extends Controller
{

    public function calificarParcial2(Request $request ,$id_maestro){

        try{

            $datosValidados = $request->validate
        (
                [
                'alumno_id' => 'required|numeric|exists:estudiantes,id',
                'calificacion_id' => 'required|numeric|exists:calificacions,id',
                'calificacion_tareas' => 'required|numeric|min:1|max:10',
                'calificacion_examen' => 'required|numeric|min:1|max:10',
                'asistencia' => 'required|numeric|min:1|max:10'
                ],

                [
                    'calificacion_tareas.min' => 'La calificacion debe estar en el rango de 1 a 10',
                    'calificacion_tareas.max' => 'La calificacion debe estar en el rango del 1 al 10',
                    'calificacion_examen.min' => 'La calificacion del examen debe estar en el rango del 1 al 10',
                    'calificacion_examen.max' => 'La calificacion del examen debe estar en el rango del 1 al 10',
                    'asistencia.min' => 'La asistencia debe estar en el rango de 1 a 10',
                    'asistencia.max' => 'La asistencia debe estar en el rango de 1 a 10'

                ],

        );


        $maestroBuscado = Maestro::find($id_maestro);

        if(!$maestroBuscado){

            return response()->json([

                'status' => false,
                'message' => 'No existe la informacion del maestro o no se ha registrado aun',
                'code' => 404
            ],404);
        }

        $validarDatosRepetidos = RubricaP2::where('alumno_id',$datosValidados['alumno_id'])->where('calificacion_id',$datosValidados['calificacion_id'])->where('maestro_id',$maestroBuscado['id'])->exists();

        if($validarDatosRepetidos){

            return response()->json([

                'status' => false,
                'message' => 'No puedes asignar una calificacion del parcial 2 a un alumno si ya le asignaste una calificacion',
                'code' => 400

            ],400);
        }

        $calificacionTareas = $datosValidados['calificacion_tareas'];
        $calificacionExamen = $datosValidados['calificacion_examen'];
        $asistencia = $datosValidados['asistencia'];

        $calcularCaliTareasP2 = $calificacionTareas * 7;
        $calcularCaliExamenP2 = $calificacionExamen * 3;

        $promedioTareas = $calcularCaliTareasP2 / 10;
        $promedioExamen = $calcularCaliExamenP2 / 10;

        $promedioFinal = $promedioTareas + $promedioExamen;

        $calificacionP2 = RubricaP2::create([

            'maestro_id' => $maestroBuscado['id'],
            'alumno_id' => $datosValidados['alumno_id'],
            'calificacion_id' => $datosValidados['calificacion_id'],
            'calificacion_tareas' => $calificacionTareas,
            'calificacion_examen' => $calificacionExamen,
            'asistencia' => $asistencia,
            'calificacion_final' => $promedioFinal
        ]);

        $buscarCalificacionP2 = Calificacion::where('estudiante_id',$datosValidados['alumno_id'])->where('maestro_id',$maestroBuscado['id'])->first();

        $buscarCalificacionP2->update([

            'parcial_2' => $promedioFinal
        ]);

        $buscarCalificacionP2->save();


        return response()->json([

            'status' => false,
            'message' => 'Calificacion del parcial 2 asignada al estudiante correctamente',
            'data' => $calificacionP2,
            'code' => 200

        ],200);

        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Ocurrio un error en los campos solicitados',
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
