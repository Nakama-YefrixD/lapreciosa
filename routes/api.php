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

Route::post('/codigo/buscar', 'api\agregarProductoController@buscarCodigo');
Route::post('/nombre/buscar', 'api\agregarProductoController@buscarNombre');
Route::post('/marca/buscar', 'api\agregarProductoController@buscarMarca');
Route::post('/tipo/buscar', 'api\agregarProductoController@buscarTipos');

Route::post('/agregar/productoExistente', 'api\agregarProductoController@agregarProductoExistente');
