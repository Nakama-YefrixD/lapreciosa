<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
<<<<<<< HEAD
=======

Route::post('/codigo/buscar', 'api\agregarProductoController@buscarCodigo');
Route::post('/codigo/qr/buscar', 'api\agregarProductoController@buscarCodigoQr');
Route::post('/nombre/buscar', 'api\agregarProductoController@buscarNombre');
Route::post('/marca/buscar', 'api\agregarProductoController@buscarMarca');
Route::post('/tipo/buscar', 'api\agregarProductoController@buscarTipos');

Route::post('/agregar/productoExistente', 'api\agregarProductoController@agregarProductoExistente');
Route::post('/agregar/nuevoProducto','api\agregarProductoController@nuevoProducto');
>>>>>>> d9afbde5aeae49e9addda96a006520b69c6e3f94
