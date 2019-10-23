<?php

namespace App\Http\Controllers\panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\entradas;
use App\proveedores;
use App\productos;
use Yajra\DataTables\DataTables;
use App\productosEntrada;

class entrada extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $proveedores = Proveedores::all();
        $productos = Productos::all();
        $data = array(
            'proveedores' => $proveedores,
            'productos' => $productos,
        );

        return view('almacen.entradas')->with($data);
    }

    public function tb_entradas()
    {
        $entradas = entradas::join('proveedores', 'entradas.proveedor_id', '=', 'proveedores.id')
                            ->get([
                                'entradas.id as idEntrada',
                                'proveedores.id as idProveedor',
                                'proveedores.ruc as rucProveedor',
                                'proveedores.nombre as nombreProveedor',
                                'entradas.factura as facturaEntrada',
                                'entradas.fecha as fechaEntrada',
                            ]);
        
        return Datatables::of($entradas)->make(true);
    }

    public function detalle($idEntrada)
    {
        $entradasProductos = productosEntrada::join('productos', 'productos_entradas.producto_id', '=', 'productos.id')
                                                ->where('productos_entradas.entrada_id', $idEntrada)
                                                ->get([
                                                    'productos.nombre               as nombreProducto',
                                                    'productos_entradas.precio      as precioProductoEntrada',
                                                    'productos_entradas.cantidad    as cantidadProductoEntrada',
                                                ]);
        $data = array(
            'entradasProductos' => $entradasProductos,
        );
        return view('almacen.loadsEntradas.detallesEntrada')->with($data);
    }
}

