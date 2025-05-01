<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoMaestro;
use App\Models\Maestro;
use Illuminate\Http\Request;

class MaestroCursoController extends Controller
{

   /* public function asignarCursosMaestro(Request $request, $id){

        try{

            $requestValidado = $request->validate([

                'cursos' => 'required|array',
                'cursos.*' => 'required|exists:cursos,id'

            ]);

            $maestroAsignado = Maestro::find($id);

            if(!$maestroAsignado){

                return response()->json([

                    'status' => false,
                    'message' => 'El id del maestro no se encuentra disponible',
                    'code' => 400

                ]);
            }


            //la funcion sync asigna valores masivos

            $cursosAsignados = $maestroAsignado->cursos()->sync($requestValidado['cursos']);

            return response()->json([

                'status' => true,
                'message' => 'Cursos asignados correctamente',
                'data' => $cursosAsignados,
                'code' => 201

            ],201);

        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error en los campos solicitados',
                'warning' => $e->errors(),
                'code' => 400,

            ],400);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error interno en la solicitud',
                'warning' => $e->getMessage(),
                'code' => 500


            ],500);
        }

    }*/


    //funcion para asignar un curso a un maestro

    /*public function asignarCurso(Request $request , $id){


        try{

            $requestValidado = $request->validate([

                'curso_id' => 'required|numeric|exists:cursos,id'
            ]);

            $maestro = Maestro::find($id);

            if(!$maestro){

                return response()->json([

                    'status' => false,
                    'message' => 'El maestro no se encuentra disponible',
                    'code' => 400
                ],400);

            }

            $cursoValidadoMaqueavolicamente = CursoMaestro::where('maestro_id', $maestro['id'])->exists();

            if($cursoValidadoMaqueavolicamente){

                return response()->json([

                    'status' => false,
                    'message' => 'Actualmente el maestro ya esta asignado a un curso',
                    'code' => 400,
    
    
                ],400);
    


            }


            $asignacion = CursoMaestro::create([

                'maestro_id' => $maestro['id'],
                'curso_id' => $requestValidado['curso_id']
            ]);
            

            return response()->json([

                'status' => true,
                'message' => 'Maestro asignado correctamente a un curso',
                'data' => $asignacion,
                'code' => 201

            ],201);


        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error en el campo solicitado',
                'warning' => $e->errors(),
                'code' => 400

            ],400);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error interno del codigo',
                'warning' => $e->getMessage(),
                'code' => 500

            ],500);


        }





    }*/












    
}
