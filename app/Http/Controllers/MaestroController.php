<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maestro;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Type\TrueType;

class MaestroController extends Controller
{

    public function registroMaestro(Request $request){

        try{

            $validacionesDatos = $request->validate([

                'nombre_maestro' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'matricula' => 'required|string|min:8',
                'numero_telefono' => 'required|string|max:10',
                'password' => 'required|string|min:4|max:20',
                'perfil_maestro' => 'nullable|image|mimes:png,jpg,jpeg',
                'curso_id' => 'required|numeric|exists:cursos,id'

            ]);

            $validacionesDatos['password'] = Hash::make($validacionesDatos['password']);


            //validacion para convertir la imagen a una ruta archivo

            if($request->hasFile('perfil_maestro')){

                $imagen = $request->file('perfil_maestro');

                $nombredelArchivo = time() . '.'. $imagen->getClientOriginalExtension();

                //nombre de la carpeta donde se van a guardar las imagnes(perfiles) gauradala en public

                //comando para guardar en carpeta publica:  php artisan:storage link

                $imagen->storeAs('perfiles',$nombredelArchivo, 'public');


                $validacionesDatos['perfil_maestro'] = 'perfiles/'.$nombredelArchivo;

            }

            $cursosValidadoExtremadamenteValidado = Maestro::where('curso_id',$validacionesDatos['curso_id'])->exists();

            if($cursosValidadoExtremadamenteValidado){

                return response()->json([

                    'status' => false,
                    'message' => 'No puedes asignar este curso por que ya esta impartido por un profesor',
                    'code' => 400

                ],400);

            }


            $maestroNuevo = Maestro::create($validacionesDatos);

            //asset genera una url para que lo traduzca 

            $maestroNuevo->perfil_maestro = asset('storage/' . $maestroNuevo->perfil_maestro);

            return response()->json([

                'status' => true,
                'message' => 'Maestro registrado exitosamente',
                'data' => $maestroNuevo,
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
                'message' => 'Error interno en la solicitud',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }
    }

    public function informacionMaestro($id){

        try{

            $maestroBuscado = Maestro::find($id);

            if(!$maestroBuscado){

                return response()->json([

                    'status' => false,
                    'message' => 'El maestro no se ha registrado aun',
                    'code' => 404,
                ],404);
            }


            $maestroBuscado->perfil_maestro = asset('storage/' . $maestroBuscado->perfil_maestro);


            return response()->json([

                'status' => true,
                'message' => 'Maestro buscado exitosamente',
                'data' => $maestroBuscado,
                'code' => 200 
            ],200);

        }catch(\Exception $e){

            return response()->json([

                'status' => false,
                'message' => 'Error del codigo',
                'warning' => $e->getMessage(),
                'code' => 500

            ],500);
        }
    }


    //actualizar info importante del maestro(no-password)

    public function actualizarInfoMaestro(Request $request, $id){

        try{

            $requestValidado = $request->validate([

                'nombre_maestro' => 'string|max:255',
                'apellido' => 'string|max:255',
                'numero_telefono' => 'string|max:10',
                'perfil_maestro' => 'image|mimes:png,jpg,jpeg'

            ]);


            $maestroBuscado = Maestro::find($id);

            if(!$maestroBuscado){

                return response()->json([

                    'status' => false,
                    'message' => 'El maestro no existe o no se ha registrado aun',
                    'code' => 404
                ],404);

            }

            
            if($request->hasFile('perfil_maestro')){

                $imagen = $request->file('perfil_maestro');

                $nombreImagen = time().'.'. $imagen->getClientOriginalExtension();

                $imagen->storeAs('perfil_nuevos',$nombreImagen,'public');

                $requestValidado['perfil_maestro'] = 'perfil_nuevos/'.$nombreImagen;

            }


            $maestroBuscado->update($requestValidado);

            $maestroBuscado->perfil_maestro = asset('storage/'.$maestroBuscado->perfil_maestro);

            return response()->json([

                'status' => true,
                'message' => 'Datos actualizados correctamente',
                'data' => $maestroBuscado,
                'code' => 200

            ],200);

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
                'message' => 'Error en la funcion actualizarinfoMaestro()',
                'warning' => $e->getMessage(),
                'code' => 500

            ],500);

        }
    }



    public function cambiarPassword(Request $request, $id_maestro){


        try{

            $passwordValidacion = $request->validate([

                'password' => 'required|string|min:4|max:20'
                
            ],

            [
                'password.required' => 'El password es requerido campeon',
                'password.string' => 'El password debe ser un string',
                'password.min' => 'El minimo de caracteres para actualizar el password debe ser 4',
                'password.max' => 'El maximo de caracteres para actualizar el password debe ser de 20'

            ]
        
        
        );

            $maestroBuscado = Maestro::find($id_maestro);

            if(!$maestroBuscado){

                return response()->json([

                    'status' => false,
                    'message' => 'El maestro con el id' .$id_maestro. 'no existe o no se ha registrado aun',
                    'code' => 404
                ],404);

            }


            //el hash chake sirve para verificar y comparar que las contrasena hasheadas no sean la misma ala que vas a actualizar
            if(Hash::check($passwordValidacion['password'],$maestroBuscado->password)){

                return response()->json([

                    'status' => false,
                    'message' => 'El password no puede ser el mismo que tenias intentalo de nuevo con un diferente password',
                    'code' => 400

                ],400);

            }

            $passwordValidacion['password']  = Hash::make($passwordValidacion['password']);

            $maestroBuscado->update($passwordValidacion);

            return response()->json([

                'status' => true,
                'message' => 'El password ha sido actualizado correctamente',
                'data' => $maestroBuscado,
                'code' => 200

            ],200);

        }catch(\Illuminate\Validation\ValidationException $e){

            return response()->json([

                'status' => false,
                'message' => 'Error en el campo solicitado del password',
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


    public function verallMaestros(){

        try{

            $maestros = Maestro::all();

            if($maestros->isEmpty()){

                return response()->json([

                    'status' => true,
                    'message' => 'No hay maestros aun en la base de datos',
                    'data' => [],
                    'code' => 200

                ],200);

            }

            return response()->json([

                'status' => true,
                'message' => 'Maestros buscados correctamente',
                'data' => $maestros,
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
