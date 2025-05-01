<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Estudiante;
use App\Models\Maestro;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Env;

class EntregasController extends Controller
{

    public function entregas(Request $request, $id){

        try{

            $requestValidado = $request->validate([

                'tarea_id' => 'required|numeric|exists:tareas,id',
                'archivos' => 'required|file|mimes:pdf,png,jpg,jpeg,docx',
                'fecha_entregada' => 'required|date|after_or_equal:today'

            ]);


            if($request->hasFile('archivos')){

                $nombre_archivo = $request->file('archivos');

                $rutaGuardada = time() .'.'. $nombre_archivo->getClientOriginalExtension();

                $nombre_archivo->storeAs('archivos_estudiante',$rutaGuardada,'public');

                $requestValidado['archivos'] = 'archivos_estudiante/'.$rutaGuardada;

            }

            $alumno = Estudiante::find($id);

            if(!$alumno){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro un estudiante en la base de datos o no se ha registrado aun',
                    'code' => 404

                ],404);

            }

            $buscarTarea = Tarea::find($requestValidado['tarea_id']);

            if($requestValidado['fecha_entregada']  > $buscarTarea['fecha_vencimiento']){

                return response()->json([

                    'status' => false,
                    'message' => 'Lo sentimos no puedes entregar la tarea si ya vencio',
                    'code' => 400

                ],400);

            }

            
            $tareaValidadamecansoganzodequeestavalida = Entrega::where('tarea_id',$requestValidado['tarea_id'])->where('alumno_id',$alumno['id'])->exists();

            if($tareaValidadamecansoganzodequeestavalida){

                return response()->json([

                    'status' => false,
                    'message' => 'No puedes entregar una tarea que ya fue entregada',
                    'code' => 400

                ],400);
            }

        
            $asignarEntrega = Entrega::create([

                'alumno_id' => $alumno['id'],
                'tarea_id' => $requestValidado['tarea_id'],
                'archivos' => $requestValidado['archivos'],
                'calificacion' => null,
                'comentarios_profesor' => null,
                'fecha_entregada' => $requestValidado['fecha_entregada']
            ]);


            $asignarEntrega->archivos = asset('storage/'.$asignarEntrega->archivos);


            return response()->json([

                'status' => true,
                'message' => 'Entrega realizada correctamente',
                'data' => $asignarEntrega,
                'code' => 201

            ],201);

        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validacion en los campos de entrada',
                'data' => $e->errors(),
                'code' =>400

            ],400);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error del programador xd',
                'data' => $e->getMessage(),
                'code' => 500

            ]);


        }

    }




    public function calificarTarea(Request $request, $id_tarea){


        try{

            $requestValidacion = $request->validate([

                'alumno_id' => 'required|exists:estudiantes,id',
                'calificacion' => 'required|numeric',
                'comentarios_profesor' => 'nullable|string'
    
            ]);
    
    
            $buscarTarea = Tarea::find($id_tarea);
    
    
            if(!$buscarTarea){
    
                return response()->json([
    
                    'status' => false,
                    'message' => 'Esta tarea no existe o no has asignado ninguna aun',
                    'code' => 400
    
                ],400);
    
            }


            //consulata queri con where mas facil xd
            $vermasTarea = Entrega::where('tarea_id',$buscarTarea['id'])->where('alumno_id',$requestValidacion['alumno_id'])->first();


            if(!$vermasTarea){

                return response()->json([

                    'status' => false,
                    'message' => 'El alumno aun no ha entregado la tarea',
                    'code' => 400

                ],400);

            }

            $vermasTarea->update([

                'calificacion' => $requestValidacion['calificacion'],
                'comentarios_profesor' => $requestValidacion['comentarios_profesor']   

            ]);


            return response()->json([

                'status' => true,
                'message' => 'Tarea calificada correctamente',
                'data' => $vermasTarea,
                'code' => 200

            ],200);

    
        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validacion en los campos solicitados',
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


    //con el . se usa para traer otra relacion que tenga ese modelo 
    public function verEntregasAlumno($id_tarea){

        try{

            $tareaBuscada = Tarea::with('entregas.estudiante')->find($id_tarea);


            if(!$tareaBuscada){

                return response()->json([

                    'status' => false,
                    'message' => 'La tarea aun no ha sido asignada o no existe',
                    'code' => 404
                ],404);
            }

            return response()->json([

                'status' => true,
                'message' => 'Tarea buscada correctamente y asignaciones igual',
                'data' => $tareaBuscada,
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
