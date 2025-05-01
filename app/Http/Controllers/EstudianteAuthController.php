<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Estudiante;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class EstudianteAuthController extends Controller
{

    public function loginEstudiante(Request $request){

        try{

            $credencialesValidadas = $request->validate([

                'matricula' => 'required|string|min:8',
                'password' => 'required|string|min:4|max:20'

            ]);

            if(Auth::guard('estudiantes')->attempt($credencialesValidadas)){

                $alumno = Auth::guard('estudiantes')->user();

                $token = $alumno->createToken('authToken')->plainTextToken;


                return response()->json([

                    'status' => true,
                    'message' => 'Estudiante logeado exitosamente',
                    'data' => $alumno,
                    'token' => $token,
                    'code' => 200

                ],200);
            }else{

                return response()->json([

                    'status' => false,
                    'message' => 'Credenciales invalidas hermano...',
                    'code' => 400

                ],400);

            }


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
                'message' => 'Error interno en la peticion',
                'warning' => $e->getMessage(),
                'code' => 500
            ],500);

        }


    }




    
}
