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
        $data = array(
            'proveedores' => $proveedores,
            'productos' => $productos,
            'marcas' => $marcas,
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
                                    'productos.precio as precioProducto'
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
}
