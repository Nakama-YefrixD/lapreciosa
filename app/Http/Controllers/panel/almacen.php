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

    public function tb_almacen()
    {
        $productos = Productos::join('marcas', 'productos.marca_id', '=', 'marcas.id')
                                ->get([
                                    'productos.id as idProducto',
                                    'marcas.id as idMarca',
                                    'marcas.nombre as nombreMarca',
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
            
            if($entrada->save()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "crear";
                $control->tabla = "entradas";
                $control->campos = "proveedor_id, factura, fecha";
                $control->datos = $request['proveedor'].",".$request['factura'].",".$request['fecha'];
                $control->descripcion = "Crear una entrada";
                $control->save();

                for ($x = 0; $x < count($request['producto']); $x++) {
                    $productosEntrada = new productosEntrada;
                    $productosEntrada->producto_id = $request['producto'][$x];
                    $productosEntrada->entrada_id = $entrada->id;
                    $productosEntrada->precio = $request['precio'][$x];
                    $productosEntrada->cantidad = $request['cantidad'][$x];
                    
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
                        $producto->cantidad = $producto->cantidad + $request['cantidad'][$x];
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
            $productos = new Productos;
            $productos->id = $request['codigoProductoNuevo'];
            $productos->marca_id = $request['marcaProducto'];
            $productos->tipo_id = $request['tipoProducto'];
            $productos->nombre = $request['nombreProductoNuevo'];
            $productos->cantidad = 0;
            $productos->precio = $precioVenta[1];
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
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    } 
}
