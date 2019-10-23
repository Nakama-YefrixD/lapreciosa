<?php

namespace App\Http\Controllers\panel\Configuraciones;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\descuentosProducto;
use App\productos;
use App\Control;
use Yajra\DataTables\DataTables;
use DB;

class descuentosController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $productos = productos::all();
        $data = array(
            'productos' => $productos,
        );
        return view('configuracion.descuentos.index')->with($data);
    }

    public function tb_descuentos()
    {
        $descuentos = descuentosProducto::join('productos as p', 'p.id', '=', 'descuentosproducto.producto_id')
                                            ->get([
                                                    'p.id as idProductos',
                                                    'descuentosproducto.id as idDescuentos',
                                                    'p.codigo as codigoProductos',
                                                    'p.nombre as nombreProductos',
                                                    'descuentosproducto.cantidad as cantidadDescuentos',
                                                    'descuentosproducto.porcentaje as porcentajeDescuentos',
                                                    'p.precio as precioProductos',
                                                
                                                ]);
        
        return Datatables::of($descuentos)->make(true);
    }

    public function descuentoCrear(Request $request)
    {

        DB::beginTransaction();
        try {
            
            $descuento = new descuentosProducto;
            $descuento->producto_id = $request['nombreProductoCrear']; 
            $descuento->porcentaje = $request['porcentajeCrear'];
            $descuento->cantidad = $request['cantidadCrear'];
            
            if($descuento->save()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Crear";
                $control->tabla = "descuentosProducto";
                $control->campos = "all";
                $control->datos = $request['nombreProductoCrear'].', '. $request['porcentajeCrear'].', '. $request['cantidadCrear'];
                $control->descripcion = "Crear un descuento a una cantidad de productos";
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

    public function descuentoEditar(Request $request)
    {
        DB::beginTransaction();
        try {
            
            $descuento = descuentosProducto::find($request['idEditar']);
            $descuento->producto_id = $request['codigoProductoEditar'];
            $descuento->porcentaje  = $request['porcentajeEditar'];
            $descuento->cantidad    = $request['cantidadEditar'];

            if($descuento->update()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "Editar";
                $control->tabla = "descuentosProducto";
                $control->campos = "all";
                $control->datos = $request['idEditar'].', '. $request['codigoProductoEditar'].', '. $request['porcentajeEditar'].', '. $request['cantidadDescuentos'];
                $control->descripcion = "Editar un descuento";
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
}
