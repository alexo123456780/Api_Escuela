<?php

namespace App\Http\Controllers;

use App\Models\Curso;
use App\Models\CursoMaestro;
use App\Models\Maestro;
use App\Models\Tarea;
use Illuminate\Http\Request;

class TareaController extends Controller
{

    public function crearTarea(Request $request,$id){

        try{

            $requestValidado = $request->validate([

                'titulo_tarea' => 'required|string',
                'instrucciones' => 'required|string',
                'fecha_vencimiento' => 'required|date|after:today',

            ]);

            $buscarMaestro = Maestro::find($id);

            if(!$buscarMaestro){

                return response()->json([

                    'status' => false,
                    'message' => 'El maestro no existe o no se encuentra registrado',
                    'code' => 400
                ],400);         
            }

            $cursoMaestro = $buscarMaestro['curso_id'];

            if(!$cursoMaestro){

                return response()->json([

                    'status' => false,
                    'message' => 'El maestro no esta asignado a un curso',
                    'code' => 400
                ]);

            }





            $tareaCreada = Tarea::create([

                'titulo_tarea' => $requestValidado['titulo_tarea'],
                'instrucciones' => $requestValidado['instrucciones'],
                'fecha_vencimiento' => $requestValidado['fecha_vencimiento'],
                'status' => true,
                'curso_id' => $cursoMaestro,
                'maestro_id' => $buscarMaestro['id']

            ]);

            return response()->json([

                'status' => true,
                'message' => 'Tarea creada exitosamente',
                'data' => $tareaCreada,
                'code' => 201,

            ],201);



        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error en los campos solicitados',
                'warning' => $e->errors(),
                'code' => 400
            ],400);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error en el codigo',
                'warning' => $e->getMessage(),
                'code' => 500

            ],500);

        }


    }


    public function obtenertodaslasTareas(){

        try{

            $allTareas = Tarea::all();

            if($allTareas->isEmpty()){

                return response()->json([

                    'status' => false,
                    'message' => 'Aun no hay tareas disponibles',
                    'data' => [],
                    'code' => 404

                ],404);

            }

            return response()->json([

                'status' => true,
                'message' => 'Tareas traidas correctamente',
                'data' => $allTareas,
                'code' => 200
            ],200);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Ocurrio un error en el codigo o no hay una conexion en la base de datos',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }
    }


    public function traerTareasCurso($id){

        try{

            $cursoTareas = Curso::with('tareas')->find($id);


            if(!$cursoTareas){

                return response()->json([

                    'status' =>  false,
                    'message' => 'El id del curso no exite o no ha sido registrado aun',
                    'code' => 404

                ],404);

            }

            if($cursoTareas['tareas']->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'No hay tareas asignadas aun para este curso',
                    'data' => [],
                    'code' => 200 

                ],200);

            }


            $todaslasTareasdelCurso = $cursoTareas['tareas'];

            return response()->json([

                'status' => true,
                'message' => 'Tareas del curso obtenidas correctamente',
                'data' => $todaslasTareasdelCurso,
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


    public function eliminarTarea($id_tarea){


        try{

        $tareas = Tarea::find($id_tarea);


        if(!$tareas){

            return response()->json([

                'status' => false,
                'message' => 'No existe esta tarea o no se ha creado aun',
                'code' => 404

            ],404);

        }


        $tareas->delete();

        return response()->json([

            'status' => true,
            'message' => 'Tarea eliminada exitosamente',
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
