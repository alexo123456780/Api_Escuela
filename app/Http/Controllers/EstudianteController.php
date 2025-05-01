<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Hash;

class EstudianteController extends Controller
{

    public function registrarAlumno(Request $request){

        try{

            $datosValidados = $request->validate([

                'nombre_estudiante' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'matricula' => 'required|string|min:8',
                'password' => 'required|string|min:4|max:20',
                'perfil_estudiante' => 'nullable|image|mimes:png,jpg,jpeg'
            ]);

            $datosValidados['password'] = Hash::make($datosValidados['password']);


            if($request->hasFile('perfil_estudiante')){

                $imagen = $request->file('perfil_estudiante');

                $nombredelArchivo = time(). '.'. $imagen->getClientOriginalExtension();

                $imagen->storeAs('imagenes_estudiante', $nombredelArchivo,'public');

                $datosValidados['perfil_estudiante'] = 'imagenes_estudiante/'.$nombredelArchivo;

            }


            $estudianteNuevo = Estudiante::create($datosValidados);

            $estudianteNuevo['perfil_estudiante'] = asset('storage/'.$estudianteNuevo['perfil_estudiante']);

            return response()->json([

                'status' => true,
                'message' => 'Estudiante registrado exitosamente',
                'data' => $estudianteNuevo,
                'code' => 201
            ],201);


        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de validacion en los campos requeridos',
                'warning' => $e->errors(),
                'code' => 400

            ],400);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error interno en la peticion',
                'warning' => $e->getMessage(),
                'code' => 500


            ],500);

        }

    }


    public function traerEstudiantes(){

        try{

            $estudiantes = Estudiante::all();

            if($estudiantes->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'No hay estudiantes registrados aun',
                    'data' => [],
                    'code' => 200

                ],200);

            }


            foreach($estudiantes as $estudiante){


                $estudiante['perfil_estudiante'] = asset('storage/'.$estudiante['perfil_estudiante']);

            }

            return response()->json([

                'status' => true,
                'message' => 'Estudiantes traidos correctamente',
                'data' => $estudiantes,
                'code' => 200
            ],200);


        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error del programador',
                'warning' => $e->getMessage(),
                'code' => 500

            ],500);

        }

    }


    public function obtenerInfoEstudiante($id_estudiante){

        try{

            $alumno = Estudiante::find($id_estudiante);

            if(!$alumno){
                
                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion del estudiante o no se ha registrado aun',
                    'code' => 404
                ],404);

            }

            $alumno->perfil_estudiante = asset('storage/'.$alumno->perfil_estudiante);

            return response()->json([

                'status' => true,
                'message' => 'Datos del alumo traidos correctamente',
                'data' => $alumno,
                'code' => 200
            ],200);


        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error de codificacion',
                'warning' => $e->getMessage(),
                'code' => 500


            ]);
        }
    }


    public function editarPerfil(Request $request , $id_estudiante){

        try{

            $validaciondeDatos = $request->validate(
            [

                'nombre_estudiante' => 'string|max:255',
                'apellido' => 'string|max:255',
                'perfil_estudiante' => 'image|mimes:png,jpg,jpeg'
            ],

            [
                'nombre_estudiante.string' => 'El nombre del estudiante debe ser letras no numeros',
                'nombre_estudiante.max' => 'El maximo de caracteres es de 255',
                'apellido.string' => 'El apellido debe ser letras o string',
                'apellido.max' => 'Que pato cuak cuak el maximo de caracteres es de 255',
                'perfil_estudiante.image' => 'El perfil debe ser una imagen',
                'perfil_estudiante.mimes' => 'El perfil debe ser en formato: png,jpg o jpeg'
            ]
        
        );


            $estudianteEncontrado = Estudiante::find($id_estudiante);

            if(!$estudianteEncontrado){

                return response()->json([

                    'status' => false,
                    'message' => 'No se encontro informacion del estudiante o no se ha registrado aun',
                    'code' => 404
                ],404);

            }

            if($request->hasFile('perfil_estudiante')){

                $imagen = $request->file('perfil_estudiante');

                $ruta_guardada = time(). '.'. $imagen->getClientOriginalExtension();

                $imagen->storeAs('perfil_estudiante_actualizado',$ruta_guardada,'public');

                $validaciondeDatos['perfil_estudiante'] = 'perfil_estudiante_actualizado/'.$ruta_guardada;

            }


            $estudianteEncontrado->update($validaciondeDatos);


            return response()->json([

                'status' => true,
                'message' => 'La informacion del estudiante ha sido actualizada correctamente',
                'data' => $estudianteEncontrado,
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
                'message' => 'Error en la codificacion y metodo',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);


        }
    }


    public function actualizarPassword(Request $request , $id_alumno){

        try{

            $passwordValidado = $request->validate([

                'password' => 'required|string|min:4|max:20'

            ],
            [
                'password.required' => 'El password debe ser obligatorio',
                'password.string' => 'El password debe ser una cadena de texto',
                'password.min' => 'El minimo de caracteres es de 4',
                'password.max' => 'El maximo de caracteres es de 20 '

            ]
        );





        }catch(\Illuminate\Validation\ValidationException $e){



        }







    }





    
















    
}
 