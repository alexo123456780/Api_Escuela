<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use App\Models\Tarea;

use function Pest\Laravel\call;

class CursoController extends Controller
{

    public function agregarCurso(Request $request){

        try{

            $requestValidado = $request->validate([

                'nombre_curso' => 'required|string|max:255',
                'descripcion_curso' => 'required|string',
                'imagen_curso' => 'required|string'
                
            ]);

            $cursoNuevo  = Curso::create($requestValidado);

            return response()->json([

                'status' => true,
                'message' => 'Curso creado exitosamente',
                'data' => $cursoNuevo,
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
                'message' => 'Error interno del servidor',
                'warning' => $e->getMessage(),
                'code' => 500

            ],500);
        }
    }


    public function mostrarCursos(){

        try{

            //se usa get para cargar una relacion y all sin nada de relaciones

            $cursosDisponibles = Curso::with('maestro')->get();

            if($cursosDisponibles->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'No hay cursos aun disponibles',
                    'data' => [],
                    'code' => 200

                ],200);

            }


            return response()->json([

                'status' => true,
                'message' => 'Cursos obtenidos correctamente',
                'data' => $cursosDisponibles,
                'code' => 200

            ],200);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error en el codigo',
                'warning' => $e->getMessage(),
                'code' => 500


            ],500);

        }
    }

    public function traerCursoMaestro($id){

        try{

            $cursoBuscado = Curso::find($id);

            if(!$cursoBuscado){

                return response()->json([

                    'status' => false,
                    'message' => 'El curso aun no ha sido dado de alta o registrado',
                    'code' => 404
                ],404);

            }



            return response()->json([

                'status' => true,
                'message' => 'Curso obtenido correctamente',
                'data' => $cursoBuscado,
                'code' => 200
            ],200);


        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error del  codigo o del programador xd',
                'warning' => $e->getMessage(),
                'code' => 500


            ],500);
        }
    }



    public function verInfoCurso($id_curso){

        try{

            $infoCurso = Curso::with('maestro')->find($id_curso);

            if(!$infoCurso){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion del curso',
                    'code' => 404
                ],404);

            }


            return response()->json([

                'status' => true,
                'message' => 'Info del curso traido correctamente',
                'data' => $infoCurso,
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


    public function verTareasCurso($id_curso){

        try{

            $tareasCurso = Curso::with('tareas')->find($id_curso);

            if(!$tareasCurso){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion del curso solicitado',
                    'code' => 404
                ],404);

            }


            return response()->json([

                'status' => true,
                'message' => 'Curso obtenido correctamente',
                'data' => $tareasCurso,
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
