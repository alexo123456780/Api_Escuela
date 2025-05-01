<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Maestro;
use Illuminate\Http\Request;

use function Pest\Laravel\call;

class CalificacionesController extends Controller
{


    public function registrarAlumnoCalificacion(Request $request ,$id_maestro){

        try{

            $alumnoValidado = $request->validate([

                'estudiante_id' => 'required|numeric|exists:estudiantes,id'

            ],

            [
                'estudiante_id.required' => 'El id del estudiante es requerido',
                'estudiante_id.numeric' => 'El id debe ser un valor numerico',
                'estudiante_id.exists' => 'El id del estudiante no existe por lo tanto no puedes asignarle una boleta'

            ]
        
        );


        $maestro = Maestro::find($id_maestro);

        if(!$maestro){

            return response()->json([

                'status' => false,
                'message' => 'El maestro no existe o no se ha registrado aun en la base de datos',
                'code' => 404

            ],404);

        }

        $noRepetidos = Calificacion::where('estudiante_id',$alumnoValidado['estudiante_id'])->where('maestro_id',$maestro['id'])->exists();

        if($noRepetidos){

            return response()->json([

                'status' => false,
                'message' => 'No puedes asignar una boleta a un alumno que ya esta asignado a esta misma',
                'code' => 400
            ],400);

        }


        $boletaCreada = Calificacion::create([

            'maestro_id' => $maestro['id'],
            'estudiante_id' => $alumnoValidado['estudiante_id'],
            'parcial_1' => null,
            'parcial_2' => null,
            'parcial_3' => null,
            'promedio_final' => null

        ]);


        return response()->json([

            'status' => true,
            'message' => 'Alumno asignado correctamente a una boleta',
            'data' => $boletaCreada,
            'code' => 201
        ],201);

        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error en el campo solicitado del id  estudiante',
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


    public function traerBoletasAlumnos(){

        try{

            $boletas = Calificacion::all();


            if($boletas->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'Aun no hay registro de las boletas de los alumnos',
                    'code' => 200
                ],200);

            }


            return response()->json([

                'status' => true,
                'message' => 'Boletas traidas correctamente',
                'data' => $boletas,
                'code' => 200
            ],200);

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
