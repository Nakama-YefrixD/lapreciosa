<?php

namespace App\Http\Controllers\panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Productos;
use App\Proveedores;
use App\Entradas;
use App\productosEntrada;
use App\control;
use App\Marcas;
use App\Tipos;
use Yajra\DataTables\DataTables;
use DB;

class almacen extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

<<<<<<< HEAD

=======
>>>>>>> d9afbde5aeae49e9addda96a006520b69c6e3f94
    public function index()
    {
        $proveedores = Proveedores::all();
        $productos = Productos::all();
        $marcas = Marcas::all();
        $tipos = Tipos::all();
        $data = array(
            'proveedores' => $proveedores,
            'productos' => $productos,
            'marcas' => $marcas,
            'tipos' => $tipos,
        );

        return view('almacen.index')->with($data);
    }

    public function tb_almacen(Request $request)
    {
        $productos = Productos::join('marcas', 'productos.marca_id', '=', 'marcas.id')
                                ->join('tipos', 'tipos.id', '=', 'productos.tipo_id')
                                ->where(function ($query) use($request) {
                                    if($request->get('bcodigo') != ''){
                                        $query->where('productos.codigo', 'like', '%' . $request->get('bcodigo') . '%');
                                    }
                    
                                    if($request->get('bmarca') != ''){
                                        $query->where('marcas.nombre', 'like', '%' . $request->get('bmarca') . '%');
                                    }
                    
                                    if($request->get('btipo') != ''){
                                        $query->where('tipos.nombre', 'like', '%' . $request->get('btipo') . '%');
                                    }

                                    if($request->get('bnombre') != ''){
                                        $query->where('productos.nombre', 'like', '%' . $request->get('bnombre') . '%');
                                    }
                                })
                                ->get([
                                    'productos.id as idProducto',
                                    'productos.codigo as codigoProducto',
                                    'marcas.id as idMarca',
                                    'marcas.nombre as nombreMarca',
                                    'tipos.id as idTipo',
                                    'tipos.nombre as nombreTipo',
                                    'productos.nombre as nombreProducto',
                                    'productos.cantidad as cantidadProducto',
                                    'productos.precio as precioProducto',
                                    'productos.precioVista as precioVistaProducto'
                                ]);
        
        return Datatables::of($productos)->make(true);
    }

    public function entradaCrear(Request $request)
    {
        DB::beginTransaction();
        try {
            $entrada = new Entradas;
            $entrada->proveedor_id = $request['proveedor'];
            $entrada->factura = $request['factura'];
            $entrada->fecha = $request['fecha'];
	        $entrada->ruc = "454545454";
            
            if($entrada->save()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "crear";
                $control->tabla = "entradas";
                $control->campos = "proveedor_id, factura, fecha";
                $control->datos = $request['proveedor'].",".$request['factura'].",".$request['fecha'];
                $control->descripcion = "Crear una entrada";
                $control->save();

<<<<<<< HEAD
                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $productosEntrada = new productosEntrada;
                    $productosEntrada->producto_id = $request['producto'][$x];
                    $productosEntrada->entrada_id = $entrada->id;
                    $productosEntrada->precio = $request['precio'][$x];
                    $productosEntrada->cantidad = $request['cantidad'][$x];
=======
                for ($x = 0; $x < count($request['producto']); $x++) {
                    $productosEntrada               = new productosEntrada;
                    $productosEntrada->producto_id  = $request['producto'][$x];
                    $productosEntrada->entrada_id   = $entrada->id;
                    $productosEntrada->precio       = $request['precio'][$x];
                    $productosEntrada->cantidad     = $request['cantidad'][$x];
>>>>>>> d9afbde5aeae49e9addda96a006520b69c6e3f94
                    
                    if($productosEntrada->save()){
                        $control = new control;
                        $control->user_id = auth()->id();
                        $control->metodo = "crear";
                        $control->tabla = "productosEntrada";
                        $control->campos = "producto_id, entrada_id, precio, cantidad";
                        $control->datos = $request['producto'][$x].",".$entrada->id.",".$request['precio'][$x].",".$request['cantidad'][$x];
                        $control->descripcion = "Crear los productos que tiene una entrada";
                        $control->save();

                        $producto = Productos::find($request['producto'][$x]);
<<<<<<< HEAD
                        $producto->cantidad = $producto->cantidad + $request['cantidad'][$x];
=======
                        $producto->cantidad = $producto->cantidad   + $request['cantidad'][$x];
                        $producto->total    = $producto->total      + $request['cantidad'][$x];
>>>>>>> d9afbde5aeae49e9addda96a006520b69c6e3f94
                        if($producto->update()){
                            $control = new control;
                            $control->user_id = auth()->id();
                            $control->metodo = "actualizar";
                            $control->tabla = "Productos";
                            $control->campos = "cantidad";
                            $control->datos = $request['cantidad'][$x];
                            $control->descripcion = "Actualizar la cantidad de productos";
                            $control->save();    
                        }
                    }
                }
            }

            DB::commit();

            $rpta = array(
                'response'      =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }    

    public function proveedorCreear(Request $request)
    {
        DB::beginTransaction();
        try {

            $proveedores = new Proveedores;
            $proveedores->nombre = $request['nombreProveedor'];
            $proveedores->ruc = $request['rucProveedor'];
            $proveedores->numero = $request['telefonoProveedor'];
            $proveedores->direccion = $request['direccionProveedor'];
            $proveedores->tipo = $request['tipoProveedor'];
            
            if($proveedores->save()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "crear";
                $control->tabla = "proveedores";
                $control->campos = "all";
                $control->datos = $request['nombreProveedor'].",".$request['rucProveedor'].",".$request['telefonoProveedor'].",".$request['direccionProveedor'].",".$request['tipoProveedor'];
                $control->descripcion = "Crear un nuevo proveedor";
                $control->save();
            }

            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'idProveedor'      =>  $proveedores->id,
                'nombreProveedor'      =>  $proveedores->nombre,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }  

    public function marcaCrear(Request $request)
    {
        DB::beginTransaction();
        try {

            $marcas = new Marcas;
            $marcas->nombre = $request['nombreMarca'];
            
            if($marcas->save()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "crear";
                $control->tabla = "Marcas";
                $control->campos = "all";
                $control->datos = $request['nombreMarca'];
                $control->descripcion = "Crear una nueva marca";
                $control->save();
            }

            DB::commit();

            $rpta = array(
                'response'      =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    } 

    public function tipoCrear(Request $request)
    {
        DB::beginTransaction();
        try {

            $tipos = new Tipos;
            $tipos->nombre = $request['nuevoTipoProducto'];
            
            if($tipos->save()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "crear";
                $control->tabla = "Tipos";
                $control->campos = "all";
                $control->datos = $request['nuevoTipoProducto'];
                $control->descripcion = "Crear un nuevo tipo de producto";
                $control->save();
            }

            DB::commit();

            $rpta = array(
                'response'      =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function productoCrear(Request $request)
    {
        DB::beginTransaction();
        try {
            $precioVenta = explode("S/", $request['precioVentaProducto']);

<<<<<<< HEAD
            if(sizeof($precioVenta) > 1){       
=======
            if(sizeof($precioVenta) > 1){
>>>>>>> d9afbde5aeae49e9addda96a006520b69c6e3f94
                $prePrecioVenta = $precioVenta[1];
            }else{
                $prePrecioVenta = $precioVenta[0];
            }
            
            $precioVentaEntero = explode(",", $prePrecioVenta);
            if(sizeof($precioVentaEntero) > 1){
                $precioVentaFinal = $precioVentaEntero[0].$precioVentaEntero[1];
            }else{
                $precioVentaFinal = $precioVentaEntero[0];
            }

            $productos = new Productos;
            $productos->codigo = $request['codigoProductoNuevo'];
            $productos->marca_id = $request['marcaProducto'];
            $productos->tipo_id = $request['tipoProducto'];
            $productos->nombre = $request['nombreProductoNuevo'];
            $productos->cantidad = 0;
<<<<<<< HEAD
=======
            $productos->total = 0;
            $productos->vendido = 0;
>>>>>>> d9afbde5aeae49e9addda96a006520b69c6e3f94
            $productos->precio = $precioVentaFinal;
            $productos->precioVista = $request['precioVentaProducto'];
            
            if($productos->save()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "crear";
                $control->tabla = "productos";
                $control->campos = "all";
                $control->datos = $request['codigoProductoNuevo'].', '. $request['marcaProducto'].', '. $request['nuevoTipoProducto'].', '. $request['nombreProductoNuevo'].', '. $request['precioVentaProducto'];
                $control->descripcion = "Crear un nuevo producto con cantidad 0";
                $control->save();
            }

            DB::commit();

            $rpta = array(
                'response'          =>  true,
                'idProducto'        =>  $productos->id,
                'nombreProducto'    =>  $productos->nombre,
                'codigoProducto'    =>  $productos->codigo,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    } 

    public function productoEditar(Request $request)
    {
        DB::beginTransaction();
        try {
            $precioVenta = explode("S/", $request['editarPrecioVentaProducto']);

            if(sizeof($precioVenta) > 1){
                $prePrecioVenta = $precioVenta[1];
            }else{
                $prePrecioVenta = $precioVenta[0];
            }
            
            $precioVentaEntero = explode(",", $prePrecioVenta);
            if(sizeof($precioVentaEntero) > 1){
                $precioVentaFinal = $precioVentaEntero[0].$precioVentaEntero[1];
            }else{
                $precioVentaFinal = $precioVentaEntero[0];
            }

            //miomio
            $productos = Productos::find($request['editarIdProducto']);
            $productos->codigo = $request['editarCodigoProductoNuevo'];
            $productos->marca_id = $request['editarMarcaProducto'];
            $productos->tipo_id = $request['editarTipoProducto'];
            $productos->nombre = $request['editarNombreProductoNuevo'] ;
            $productos->precio = $precioVentaFinal;
            $productos->precioVista = $request['editarPrecioVentaProducto'];
            
<<<<<<< HEAD
            if($productos->update()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Editar";
                $control->tabla = "productos";
                $control->campos = "codigo, marca_id, tipo_id, nombre, precio, precioVista";
                $control->datos = $request['editarIdProducto'].', '. $request['editarCodigoProductoNuevo'].', '. $request['editarMarcaProducto'].', '. $request['editarTipoProducto'].', '. $request['editarNombreProductoNuevo'].', '. $request['editarPrecioVentaProducto'];
                $control->descripcion = "Editar un producto";
=======
            
            if($request['editarCantidadProducto'] == $productos->cantidad){

            }else{
                $entradaEditar = Entradas::find(10000);
                if($entradaEditar){
                    $idEntrada = $entradaEditar->id;
                }else{
                    $entrada = new Entradas;
                    $entrada->id = 10000;
                    $entrada->proveedor_id = 1;
                    $entrada->factura = "01";
                    $entrada->fecha = "2019-08-14";
                    $entrada->ruc = "454545454";
                    $entrada->save();
                    $idEntrada = $entrada->id;
                }

                $productosEntrada               = new productosEntrada;
                $productosEntrada->producto_id  = $productos->id;
                $productosEntrada->entrada_id   = $idEntrada;
                $productosEntrada->precio       = $request['editarPrecioCosto'];
                $productosEntrada->cantidad     = $request['editarCantidadProducto'] - $productos->cantidad;
                
                if($productosEntrada->save()){
                    $productos->cantidad        = $request['editarCantidadProducto'];
                    $productos->total           = $productos->total + $productosEntrada->cantidad;
                }
            }
            

            if($productos->update()) {
                $control = new control;
                $control->user_id       = auth()->id();
                $control->metodo        = "Editar";
                $control->tabla         = "productos";
                $control->campos        = "codigo, marca_id, tipo_id, nombre, precio, precioVista";
                $control->datos         = $request['editarIdProducto'].', '. $request['editarCodigoProductoNuevo'].', '. $request['editarMarcaProducto'].', '. $request['editarTipoProducto'].', '. $request['editarNombreProductoNuevo'].', '. $request['editarPrecioVentaProducto'];
                $control->descripcion   = "Editar un producto";
>>>>>>> d9afbde5aeae49e9addda96a006520b69c6e3f94
                $control->save();
            }

            DB::commit();

            $rpta = array(
                'response'          =>  true,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    } 

<<<<<<< HEAD
    public function ejemplo(Request $request){

        echo $request['codigoP'];

    }
=======
>>>>>>> d9afbde5aeae49e9addda96a006520b69c6e3f94
}
