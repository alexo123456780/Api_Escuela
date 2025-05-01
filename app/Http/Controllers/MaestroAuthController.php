<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Maestro;
use Illuminate\Support\Facades\Auth;

class MaestroAuthController extends Controller
{

    public function loginMaestro(Request $request){

        try{

            $credencialesValidadas = $request->validate([

                'matricula' => 'required|string|min:8',
                'password' => 'required|string|min:4|max:20'

            ]);


            if(Auth::attempt($credencialesValidadas)){

                $maestro = Auth::user();

                $token = $maestro->createToken('authToken')->plainTextToken;


                return response()->json([

                    'status' => true,
                    'message' => 'Maestro logeado exitosamente',
                    'data' => $maestro,
                    'token' => $token,
                    'code' => 200

                ],200);

            }else{

                return response()->json([

                    'status' => false,
                    'message' => 'Credenciales incorrectas, intente de nuevo porfavor...',
                    'code' => 400

                ],400);

            }

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
                'message' => 'Error interno en la peticion',
                'warning' => $e->getMessage(),
                'code' => 500

            ],500);

        }


    }


    
}
