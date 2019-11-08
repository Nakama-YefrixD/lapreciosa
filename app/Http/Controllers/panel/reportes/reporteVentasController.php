<?php

namespace App\Http\Controllers\panel\reportes;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ventas;
use App\Exports\VentasExport;

use Yajra\DataTables\DataTables;
use Excel;

class reporteVentasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('reportes.ventas.index');
    }

    public function tb_ventas(Request $request)
    {
        $ventas = ventas::join('detalles_venta as dv', 'dv.venta_id', '=', 'ventas.id')
                        ->join('clientes as c', 'ventas.cliente_id', '=', 'c.id')
                        ->join('tiposcomprobante as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
                        ->join('productos as p', 'p.id', '=', 'dv.producto_id')
                        ->where(function ($query) use($request) {
                            if($request->get('bcliente') != ''){
                                $query->where('c.nombre', 'like', '%' . $request->get('bcliente') . '%');
                            }
            
                            if($request->get('bcomprobante') != ''){
                                $query->where('tc.nombre', 'like', '%' . $request->get('bcomprobante') . '%');
                            }

                            if($request->get('bnumeroComprobante') != ''){
                                $query->where('ventas.numero',  $request->get('bnumeroComprobante'));
                            }
            
                            if($request->get('dateOne') != ''){
                                $query->whereBetween('ventas.fecha',  [$request->get('dateOne'), $request->get('dateTwo')]);
                            }
                        })
                        ->orderBy('ventas.numero', 'asc')
                        ->get([
                            'ventas.id                  as idVenta',
                            'ventas.fecha               as fechaVentas',
                            'c.documento                as documentoClientes',
                            'c.nombre                   as nombreClientes',
                            'tc.nombre                  as nombreTiposcomprobante',
                            'tc.serie                   as serieTiposcomprobante',
                            'ventas.numero              as numeroVentas',
                            'ventas.estadoSunat         as estadoSunatVentas',
                            'p.nombre                   as nombreProducto',
                            'dv.cantidad                as cantidadDetalleVenta',
                            'p.precio                   as precioProducto',
                            'dv.subtotal                as subTotalProductoVenta',
                            'dv.total                   as totalProductoVenta',
                            'ventas.subtotal            as subTotalVentas',
                            'ventas.total               as totalVentas',
                        ]);
        
        return Datatables::of($ventas)->make(true);
    }


    public function generarReporte($cliente, $tipoComprobante, $numeroComprobante, $primeraFecha, $segundaFecha)
    {

        // return dd($request);
        return Excel::download(
            new VentasExport(
                $cliente,
                $tipoComprobante,
                $numeroComprobante,
                $primeraFecha,
                $segundaFecha
            ), 
            'ventas.xlsx'
        );
    }


}
