<?php

namespace App\Http\Controllers;

use App\productos;
use App\productosEntrada;
use App\control;
use Illuminate\Http\Request;
use DB;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public static function index()
    {
        $productos = productos::all();
        return $productos;        
    }

    public static function buscadorProductos()
    {
        $productos = productos::select( 'productos.id           as id', 
                                        'productos.marca_id     as marca_id', 
                                        'productos.tipo_id      as tipo_id', 
                                        'productos.cantidad     as cantidad',
                                        'productos.precio       as precio',
                                        'productos.precioVista  as precioVista',
                                        DB::raw("CONCAT(productos.codigo,'-',productos.nombre) AS nombre"),
                                        'dp.producto_id         as idProductoDescuento',
                                        'dp.porcentaje          as porcentajeProductoDescuento',
                                        'dp.cantidad            as cantidadProductoDescuento'
                                        )
                                ->leftjoin('descuentosproducto as dp', 'dp.producto_id', '=','productos.id')
                                ->get();
        return $productos;
    }
    public function codigo()
    {
        $productos = productos::all();
        foreach($productos as $producto){

            $producto = Productos::find($producto->id);
            $producto->codigo = $producto->id;
            $producto->update();

        }
    }

    public function eliminarProducto(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $productosEntrada = productosEntrada::where('producto_id', $request['id']);
            if($productosEntrada->delete()){
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Eliminar";
                $control->tabla = "productosEntrada";
                $control->campos = "producto_id";
                $control->datos = $request['id'];
                $control->descripcion = "Eliminar todas los productos de una entrada que conincidan con el producto especifico";
                $control->save();
                
                $productos = productos::find($request['id']);
                if($productos->delete()){
                    $control = new control;
                    $control->user_id = auth()->id();
                    $control->metodo = "Eliminar";
                    $control->tabla = "Productos";
                    $control->campos = "id";
                    $control->datos = $request['id'];
                    $control->descripcion = "Eliminar un producto";
                    $control->save();
                }
            }else{
                $productos = productos::find($request['id']);
                if($productos->delete()){
                    $control = new control;
                    $control->user_id = auth()->id();
                    $control->metodo = "Eliminar";
                    $control->tabla = "Productos";
                    $control->campos = "id";
                    $control->datos = $request['id'];
                    $control->descripcion = "Eliminar un producto";
                    $control->save();
                }
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function show(productos $productos)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function edit(productos $productos)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, productos $productos)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\productos  $productos
     * @return \Illuminate\Http\Response
     */
    public function destroy(productos $productos)
    {
        //
    }
}
