<?php

use App\Http\Controllers\CalificacionesController;
use App\Http\Controllers\CursoController;
use App\Http\Controllers\EntregasController;
use App\Http\Controllers\EstudianteAuthController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\MaestroAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MaestroController;
use App\Http\Controllers\MaestroCursoController;
use App\Http\Controllers\Parcial1DetallesController;
use App\Http\Controllers\Parcial2DetallesController;
use App\Http\Controllers\Parcial3DetallesController;
use App\Http\Controllers\TareaController;


//seccion Maestros
Route::post('registro-maestro',[MaestroController::class,'registroMaestro']);
Route::post('login-maestro',[MaestroAuthController::class,'loginMaestro']);
Route::post('registar-curso-maestro/{id}',[MaestroCursoController::class,'asignarCurso']);
Route::get('buscar-maestro/{id}',[MaestroController::class,'informacionMaestro']);
Route::put('update-maestro/{id}',[MaestroController::class,'actualizarInfoMaestro']);
Route::put('actualizar-password/{id}',[MaestroController::class,'cambiarPassword']);
Route::get('ver-todos-maestros',[MaestroController::class,'verallMaestros']);


//cursos

Route::post('agregar-curso',[CursoController::class, 'agregarCurso']);
Route::get('mostrar-cursos',[CursoController::class, 'mostrarCursos']);
Route::get('mostrar-unico-curso/{id}',[CursoController::class,'traerCursoMaestro']);
Route::get('mostrar-cursoA/{id}',[CursoController::class,'verInfoCurso']);
Route::get('mostrar-tareas-curso/{id}',[CursoController::class,'verTareasCurso']);



//entregas

Route::post('entrega-alumno/{id}',[EntregasController::class,'entregas']);
Route::get('ver-entrega-todoA/{id}',[EntregasController::class,'verEntregasAlumno']);


//seccion alumnos
Route::post('registro-alumno',[EstudianteController::class,'registrarAlumno']);
Route::post('login-estudiante',[EstudianteAuthController::class,'loginEstudiante']);
Route::get('traer-alumnos',[EstudianteController::class,'traerEstudiantes']);
Route::get('verinfo-estudiante/{id}',[EstudianteController::class,'obtenerInfoEstudiante']);
Route::put('editar-perfil-estudiante/{id}',[EstudianteController::class,'editarPerfil']);
Route::put('editar-password/{id}',[EstudianteController::class,'actualizarPassword']);


//seccion Tarea

Route::get('traer-tareas',[TareaController::class,'obtenertodaslasTareas']);
Route::post('crear-tarea/{id}',[TareaController::class,'crearTarea']);
Route::get('traer-tareas-curso/{id}',[TareaController::class,'traerTareasCurso']);
Route::put('calificar-tarea/{id}',[EntregasController::class,'calificarTarea']);
Route::delete('eliminar-tarea/{id}',[TareaController::class,'eliminarTarea']);

//calificaciones:

Route::post('subir-boleta-alumno/{id}',[CalificacionesController::class,'registrarAlumnoCalificacion']); 
Route::get('traer-todas-boletas',[CalificacionesController::class,'traerBoletasAlumnos']);   






Route::post('calificar-parcial1/{id}',[Parcial1DetallesController::class,'calificarParcial1']);
Route::post('calificar-parcial2/{id}',[Parcial2DetallesController::class,'calificarParcial2']);
Route::post('subir-calificacionp3/{id}',[Parcial3DetallesController::class,'subirCalificacionP3']);
