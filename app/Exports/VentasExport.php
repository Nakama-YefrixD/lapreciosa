<?php

namespace App\Exports;

use App\ventas;
use Maatwebsite\Excel\Concerns\FromCollection;

class VentasExport implements FromCollection
{
    public $cliente;
    public $tipoComprobante;
    public $numeroComprobante;
    public $primeraFecha;
    public $segundaFecha;
    
    
    /**
    * @return \Illuminate\Support\Collection
    */

    public function __construct($cliente, $tipoComprobante, $numeroComprobante, $primeraFecha, $segundaFecha)
    {
        $this->cliente              = $cliente;
        $this->tipoComprobante      = $tipoComprobante;
        $this->numeroComprobante    = $numeroComprobante;
        $this->primeraFecha         = $primeraFecha;
        $this->segundaFecha         = $segundaFecha;
    }

    public function collection()
    {
        $cliente            = $this->cliente;
        $tipoComprobante    = $this->tipoComprobante;
        $numeroComprobante  = $this->numeroComprobante;
        $primeraFecha       = $this->primeraFecha;
        $segundaFecha       = $this->segundaFecha;

        $ventas = ventas::leftjoin('notascreditos as ntc', 'ntc.venta_id', '=','ventas.id')
                        ->join('detalles_venta as dv', 'dv.venta_id', '=', 'ventas.id')
                        ->join('clientes as c', 'ventas.cliente_id', '=', 'c.id')
                        ->join('tiposcomprobante as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
                        ->join('productos as p', 'p.id', '=', 'dv.producto_id')
                        ->where(function ($query) use($cliente, $tipoComprobante, $numeroComprobante, $primeraFecha, $segundaFecha) {
                            if($primeraFecha != '' && $primeraFecha != null && $primeraFecha != 'null'){
                                $query->whereBetween('ventas.fecha',  [$primeraFecha, $segundaFecha]);
                            }

                            if($cliente != '' && $cliente != null && $cliente != 'null'){
                                $query->where('c.nombre', 'like', '%' . $cliente . '%');
                            }
            
                            if($tipoComprobante != '' && $tipoComprobante != null && $tipoComprobante != 'null'){
                                $query->where('tc.nombre', 'like', '%' . $tipoComprobante . '%');
                            }

                            if($numeroComprobante != '' && $numeroComprobante != null && $numeroComprobante != 'null'){
                                $query->where('ventas.numero',  $numeroComprobante );
                            }
                        })
                        ->orderBy('ventas.numero', 'asc')
                        ->get([
                            'ventas.fecha               as fechaVentas',
                            'c.documento                as documentoClientes',
                            'c.nombre                   as nombreClientes',
                            'tc.nombre                  as nombreTiposcomprobante',
                            'tc.serie                   as serieTiposcomprobante',
                            'ventas.numero              as numeroVentas',
                            'ventas.estadoSunat         as estadoSunatVentas',
                            'ntc.motivo                 as motivoNotaCredito',
                            'p.nombre                   as nombreProducto',
                            'dv.cantidad                as cantidadDetalleVenta',
                            'p.precio                   as precioProducto',
                            'dv.descuento               as descuentoProductoVenta',
                            'dv.subtotal                as subTotalProductoVenta',
                            'dv.total                   as totalProductoVenta',
                            'ventas.subtotal            as subTotalVentas',
                            'ventas.total               as totalVentas',
                        ]);

        // if(sizeof($ventas) > 0){
        //     $ventasNotaCredito = array(
        //                             array(
        //                                 'fechaVentas'            => 0,
        //                                 'documentoClientes'      => 0,
        //                                 'nombreClientes'         => 0,
        //                                 'nombreTiposcomprobante' => 0,
        //                                 'serieTiposcomprobante'  => 0,
        //                                 'numeroVentas'           => 0,
        //                                 'notaCredito'            => 0,
        //                                 'motivoNotaCredito'      => 0,
        //                                 'nombreProducto'         => 0,
        //                                 'cantidadDetalleVenta'   => 0,
        //                                 'precioProducto'         => 0,
        //                                 'subTotalProductoVenta'  => 0,
        //                                 'totalProductoVenta'     => 0,
        //                                 'subTotalVentas'         => 0,
        //                                 'totalVentas'            => 0,
        //                             ),
        //                         );
        //     $cont = 0;
        //     foreach($ventas as $venta){
                
        //         $ventasNotaCredito[$cont]['fechaVentas']            = $venta->fechaVentas  ;
        //         $ventasNotaCredito[$cont]['documentoClientes']      = $venta->documentoClientes  ;
        //         $ventasNotaCredito[$cont]['nombreClientes']         = $venta->nombreClientes  ;
        //         $ventasNotaCredito[$cont]['nombreTiposcomprobante'] = $venta->nombreTiposcomprobante  ;
        //         $ventasNotaCredito[$cont]['serieTiposcomprobante']  = $venta->serieTiposcomprobante  ;
        //         $ventasNotaCredito[$cont]['numeroVentas']           = $venta->numeroVentas  ;

        //         if( $venta->estadoSunatVentas == 2 ){
        //             $ventasNotaCredito[$cont]['notaCredito']            = "NOTA DE CREDITO"  ; 
        //             $ventasNotaCredito[$cont]['motivoNotaCredito']      = $venta->motivoNotaCredito  ; 
        //         }else{
        //             $ventasNotaCredito[$cont]['notaCredito']            = "NA"; 
        //             $ventasNotaCredito[$cont]['motivoNotaCredito']      = "";
        //         }


        //         $ventasNotaCredito[$cont]['nombreProducto']         = $venta->nombreProducto  ; 
        //         $ventasNotaCredito[$cont]['cantidadDetalleVenta']   = $venta->cantidadDetalleVenta  ; 
        //         $ventasNotaCredito[$cont]['precioProducto']         = $venta->precioProducto  ; 
        //         $ventasNotaCredito[$cont]['subTotalProductoVenta']  = $venta->subTotalProductoVenta  ; 
        //         $ventasNotaCredito[$cont]['totalProductoVenta']     = $venta->totalProductoVenta  ; 
        //         $ventasNotaCredito[$cont]['subTotalVentas']         = $venta->subTotalVentas  ; 
        //         $ventasNotaCredito[$cont]['totalVentas']            = $venta->totalVentas  ; 

        //         $cont = $cont +1;
        //     }
        // }
        return $ventas;
    }
}
