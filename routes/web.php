<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/perfil', 'perfilUsuario@index')->name('perfil');

Route::get('/almacen', 'panel\almacen@index')->name('almacen.index');
Route::get('/almacen/tb_almacen', 'panel\almacen@tb_almacen')->name('almacen.tabla');

Route::post('/almacen/proveedor/crear', 'panel\almacen@proveedorCreear')->name('almacen.proveedor.crear');
Route::post('/almacen/marca/crear', 'panel\almacen@marcaCrear')->name('almacen.marca.crear');
Route::post('/almacen/tipo/crear', 'panel\almacen@tipoCrear')->name('almacen.tipo.crear');
Route::post('/almacen/producto/crear', 'panel\almacen@productoCrear')->name('almacen.producto.crear');
Route::post('/almacen/entrada/crear', 'panel\almacen@entradaCrear')->name('almacen.entrada.crear');

Route::get('/almacen/load/tiposProductos', function () {
    return view('almacen.loads.tiposProductos');
});

Route::get('/almacen/load/productos', function () {
    return view('almacen.loads.productos');
});

Route::get('/almacen/load/marcas', function () {
    return view('almacen.loads.marcas');
});


Route::post('/consult/ruc/{ruc}', 'consultaIdentidad@rucConsult')->name('consult');



