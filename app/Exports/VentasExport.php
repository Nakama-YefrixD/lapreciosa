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

        $ventas = ventas::
                        join('clientes as c', 'ventas.cliente_id', '=', 'c.id')
                        ->join('tiposcomprobante as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
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
                            // 'ventas.estadoSunat         as estadoSunatVentas',
                            // 'ntc.motivo                 as motivoNotaCredito',
                            // 'p.nombre                   as nombreProducto',
                            // 'dv.cantidad                as cantidadDetalleVenta',
                            // 'p.precio                   as precioProducto',
                            // 'dv.descuento               as descuentoProductoVenta',
                            // 'dv.subtotal                as subTotalProductoVenta',
                            // 'dv.total                   as totalProductoVenta',
                            'ventas.subtotal            as subTotalVentas',
                            'ventas.total               as totalVentas',
                        ]);

        return $ventas;
    }
}
