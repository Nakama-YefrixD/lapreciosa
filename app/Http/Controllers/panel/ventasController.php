<?php

namespace App\Http\Controllers\panel;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\ventas;
use App\clientes;
use App\detallesventa;
use App\tiposcomprobante;
use App\tiposdocumento;
use App\tiposmoneda;
use App\productos;
use App\control;
use App\User;
use Yajra\DataTables\DataTables;

use Greenter\Ws\Services\SunatEndpoints;
use Greenter\See;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Company;
use Greenter\Model\Company\Address;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Sale\Legend;
use NumerosEnLetras;

use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

use DB;
use PDF;
use QrCode;
use Response;

class ventasController extends Controller
{
    public function __construct()
    {
        
    }

    public function index()
    {
        return view('ventas.index');
    }

    public function tb_ventas(Request $request)
    {
        $ventas = ventas::join('tiposcomprobante', 'ventas.tipoComprobante_id', '=', 'tiposcomprobante.id')
                        ->join('clientes', 'ventas.cliente_id', '=', 'clientes.id')
                        ->where(function ($query) use($request) {
                            if($request->get('bcliente') != ''){
                                $query->where('clientes.nombre', 'like', '%' . $request->get('bcliente') . '%');
                            }
            
                            if($request->get('bcomprobante') != ''){
                                $query->where('tiposcomprobante.nombre', 'like', '%' . $request->get('bcomprobante') . '%');
                            }

                            if($request->get('bnumeroComprobante') != ''){
                                $query->where('ventas.numero', 'like', '%' . $request->get('bnumeroComprobante') . '%');
                            }
            
                            if($request->get('dateOne') != ''){
                                $query->whereBetween('ventas.fecha',  [$request->get('dateOne'), $request->get('dateTwo')]);
                            }
                        })
                        ->get([
                            'ventas.id                  as idVentas',
                            'tiposcomprobante.id        as idTiposcomprobante',
                            'tiposcomprobante.nombre    as nombreTiposcomprobante',
                            'ventas.fecha               as fechaVentas',
                            'clientes.nombre            as nombreClientes',
                            'ventas.numero              as numeroVentas',
                            'ventas.estadoSunat         as estadoSunatVentas',
                            'ventas.subtotal            as subTotalVentas',
                            'ventas.total               as totalVentas',
                        ]);
        
        return Datatables::of($ventas)->make(true);
    }

    public function tb_buscarProducto(Request $request)
    {
        $productos = productos::leftjoin('descuentosproducto as dp', 'dp.producto_id', '=','productos.id')
                                ->join('marcas as m', 'm.id', '=', 'productos.marca_id')
                                ->join('tipos as t', 't.id', '=', 'productos.tipo_id')
                                ->where(function ($query) use($request) {
                                    if($request->get('bcodigo') != ''){
                                        $query->where('productos.codigo', 'like', '%' . $request->get('bcodigo') . '%');
                                    }
                    
                                    if($request->get('bmarca') != ''){
                                        $query->where('m.nombre', 'like', '%' . $request->get('bmarca') . '%');
                                    }

                                    if($request->get('btipo') != ''){
                                        $query->where('t.nombre', 'like', '%' . $request->get('btipo') . '%');
                                    }

                                    if($request->get('bnombre') != ''){
                                        $query->where('productos.nombre', 'like', '%' . $request->get('bnombre') . '%');
                                    }

                                    if($request->get('bprecio') != ''){
                                        $query->where('productos.precio', 'like', '%' . $request->get('bprecio') . '%');
                                    }
                                    
                                })
                                ->get([
                                    'productos.codigo     as codigoProducto',
                                    'm.nombre             as marcaProducto',
                                    't.nombre             as tipoProducto',
                                    'productos.cantidad   as disponiblesProducto',
                                    'productos.nombre     as nombreProducto',
                                    'productos.precio     as precioProducto',
                                    'productos.id         as idProducto',
                                    'dp.producto_id       as idProductoDescuento',
                                    'dp.porcentaje        as porcentajeProductoDescuento',
                                    'dp.cantidad          as cantidadProductoDescuento'
                                    
                                ]);
        
        return Datatables::of($productos)->make(true);
    }

    public function emitirFactura(Request $request)
    {   
        date_default_timezone_set("America/Mexico_City");
        $fechaActual = date('Y-m-d');
        // ENVIAR A LA SUNAT 
        $see = new See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setCertificate(file_get_contents(public_path('\sunat\certificados\certificate.pem')));
        $see->setCredentials('20605007211CITINETY'/*ruc+usuario*/, 'raulpreciosajohnson');
        // ---------- FACTURACION -------------
        $tipoDocumento    = tiposdocumento::find($request['tipoDocumento']);
        $tiposcomprobante = tiposcomprobante::find($request['tipoComprobante']);
        $tipoMoneda       = tiposmoneda::find($request['tipoMoneda']);

        // Cliente
        $client = new Client();
        $client->setTipoDoc($tipoDocumento->codigo) //6 es RUC
            ->setNumDoc($request['numeroDocumento'])
            ->setRznSocial($request['razonSocial']);

        // Emisor
        $address = new Address();
        $address->setUbigueo('040101')
            ->setDepartamento('AREQUIPA')
            ->setProvincia('AREQUIPA')
            ->setDistrito('AREQUIPA')
            ->setUrbanizacion('NONE')
            ->setDireccion('CAL. DEAN VALDIVIA 410, 412, 4 NRO. --');

        $company = new Company();
        $company->setRuc('20605007211')
                ->setRazonSocial('LA PRECIOSA DISTRIBUCIONES IMPORTACIONES E.I.R.L')
                ->setNombreComercial('PRECIOSA')
                ->setAddress($address);
        

        DB::beginTransaction();
        try {
            
            $cliente = clientes::where('documento', $request['numeroDocumento'])
                                ->first();

            if($cliente){
                $idCliente = $cliente->id;
            }else{
                $cliente = new clientes;
                $cliente->tipoDocumento_id = $request['tipoDocumento']; 
                $cliente->documento = $request['numeroDocumento'];
                $cliente->nombre = $request['razonSocial']; 
                $cliente->save();

                $idCliente = $cliente->id;
            }

            $venta = new ventas;
            $venta->tipoComprobante_id  = $request['tipoComprobante'];  
            $venta->cliente_id          = $idCliente;
            $venta->user_id             = auth()->id(); 
            $venta->tipoMoneda_id       = $request['tipoMoneda'];  
            $venta->numero              = $request['facturaVenta']; 
            $venta->fecha               = $request['dateFactura'];
            $venta->fechaVencimiento    = $request['dateFactura'];
            $venta->descuento           = $request['descuentoVenta'];
            $venta->igv                 = 18;
            $venta->impuestos           = $request['igvVenta'];
            $venta->subtotal            = $request['subTotalVenta'];
            $venta->total               = $request['totalVenta'];
            $venta->estadoEmail         = false;
            $venta->estadoSunat         = true;
            $venta->observaciones       = $request['observacionVenta'];

            if($venta->save()) {
                // Venta
                $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Catalog. 51
                ->setTipoDoc($tiposcomprobante->codigo)
                ->setSerie($request['serieVenta'])
                ->setCorrelativo( $request['facturaVenta'])
                ->setFechaEmision(new \DateTime(date("d-m-Y H:i:s", strtotime($request['dateFactura']))))
                ->setTipoMoneda($tipoMoneda->abreviatura)
                ->setClient($client)
                ->setMtoOperGravadas($venta->subtotal) //100
                ->setMtoIGV($venta->impuestos) //18
                ->setTotalImpuestos($venta->impuestos) //18
                ->setValorVenta( $venta->subtotal) //100
                ->setMtoImpVenta($venta->total) //118
                ->setCompany($company);
                $items = [];

                $tiposcomprobante               = tiposcomprobante::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo  = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesventa;
                    $ventaDetalles->venta_id    = $venta->id; 
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad    = $request['cantidad'][$x];
                    $ventaDetalles->igv         = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento   = $request['descuento'][$x];
                    $ventaDetalles->subtotal    = $request['subtotal'][$x];
                    $ventaDetalles->total       = $request['total'][$x];
                    $ventaDetalles->save();

                    $producto = Productos::find($request['nombreProducto'][$x]);
                    $producto->cantidad = $producto->cantidad - $request['cantidad'][$x];

                    if($producto->update()){

                        $producto = productos::find($request['nombreProducto'][$x]);
                        
                        $precioFinalProducto  = $producto->precio - $request['descuento'][$x];
                        $productoPrecioSinIgv = $precioFinalProducto/1.18;
                        $productoPrecioCantidadSinIgv = $productoPrecioSinIgv * $request['cantidad'][$x];
                        $productoImpuesto             = $request['total'][$x] - $request['subtotal'][$x];

                        $items[$x] = (new SaleDetail())
                            ->setCodProducto($producto->codigo)
                            ->setUnidad('NIU')
                            ->setCantidad($request['cantidad'][$x])//2
                            ->setDescripcion($producto->nombre)
                            ->setMtoBaseIgv($productoPrecioCantidadSinIgv)  //100
                            ->setPorcentajeIgv(18.00) // 18%
                            ->setIgv(sprintf("%.2f", $productoImpuesto))    //18
                            ->setTipAfeIgv('10')
                            ->setTotalImpuestos(sprintf("%.2f", $productoImpuesto))//18
                            ->setMtoValorVenta($productoPrecioCantidadSinIgv)//100
                            ->setMtoValorUnitario($productoPrecioSinIgv)//50
                            ->setMtoPrecioUnitario($precioFinalProducto);//59


                        $control = new control;
                        $control->user_id       = auth()->id();
                        $control->metodo        = "Actualizar";
                        $control->tabla         = "Productos";
                        $control->campos        = "cantidad";
                        $control->datos         = $request['cantidad'][$x];
                        $control->descripcion   = "Actualizar la cantidad de productos despues de realizar una venta";
                        $control->save();    
                    }
                }
            }

            $legend = (new Legend())
                            ->setCode('1000')
                            ->setValue(NumerosEnLetras::convertir($venta->total).'/100 SOLES');

            $invoice->setDetails($items)
                    ->setLegends([$legend]);

            $result = $see->send($invoice);

            // Guardar XML
            file_put_contents(public_path('\sunat\xml\venta-'.$venta->id.'-'.$invoice->getName().'.xml'),
                                $see->getFactory()->getLastXml());
            if (!$result->isSuccess()) {
            var_dump($result->getError());
            exit();
            }
            
            // Guardar CDR
            file_put_contents(public_path('\sunat\zip\venta-'.$venta->id.'-R-'.$invoice->getName().'.zip'), $result->getCdrZip());

            $venta      = ventas::find($venta->id);
            $venta->xml = "\sunat\xml\venta-".$venta->id."-".$invoice->getName().".xml";
            $venta->cdr = '\sunat\zip\venta-'.$venta->id.'-R-'.$invoice->getName().'.zip';
            $venta->update();

            $codigoQr = QrCode::format('png')
                                ->size(250)
                                ->generate(
                                    "20605007211|".$tiposcomprobante->codigo."|".$request['serieVenta']."|".$request['facturaVenta']."|".$venta->impuestos."|".$venta->total."|".$request['dateFactura']."|".$tipoDocumento->codigo."|".$request['numeroDocumento']."|", public_path('img/qr.png')
                                );

            // IMPRIMIR TICKET
            $nombre_impresora = "POS"; 

            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            
            try{
                $logo = EscposImage::load(public_path('img/logo.png'), false);
                $printer->bitImage($logo);
            }catch(Exception $e){/*No hacemos nada si hay error*/}


            $printer->text("\n"."LA PRECIOSA " . "\n");
            $printer->text("Direccion: Dean Valdivia 412 A" . "\n");
            $printer->text("Tel: 054 77 34 22" . "\n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("FACTURA ELECTRONICA"."\n");
            $printer->text("SERIE: ".$request['serieVenta']."-".$request['facturaVenta']."\n");
            #La fecha también
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            $printer->text("-----------------------------"."\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    
                    $producto = productos::where('id', $request['nombreProducto'][$x])
                                        ->first();

                    $printer->text($producto['nombre'].": \n");
                    $printer->text( $request['cantidad'][$x]."  unidad    ".$producto['precio']." ".$request['total'][$x]."   \n");
                    
                    
                }
                

            $printer->text("-----------------------------"."\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: ".$request['subTotalVenta']."\n");
            $printer->text("IVA: ".$request['igvVenta']."\n");
            $printer->text("TOTAL: ".$request['totalVenta']."\n");
            
            $printer->text("\n");
            
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Puede consultar en preciosaweb.com (https://preciosaweb.com/consultas)"."\n");
            $printer->text("\n");
            $printer->text("\n");
            $imgQr = EscposImage::load(public_path('img/qr.png'), false);
            $printer->bitImage($imgQr);
            $printer->text("Muchas gracias por su compra\n");

            $printer->feed(3);
            $printer->cut();
            $printer->pulse();
            $printer->close();



            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'respuestaSunat' => $result->getCdrResponse()->getDescription(),
                'setValue' => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal' => $venta->total,
                'invoice' => $invoice
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    
    public function guardarEmitirFactura(Request $request)
    {
        DB::beginTransaction();
        try {
            $correlativo = ventas::where('numero', $request['facturaVenta'])
                                ->where('tipoComprobante_id', $request['tipoComprobante'])
                                ->first();

            if($correlativo){
                
                $rpta = array(
                    'response'      =>  false,
                );
                
                return json_encode($rpta);
            }

            $cliente = clientes::where('documento', $request['numeroDocumento'])
                            ->first();
            
            if($cliente){
                $idCliente = $cliente->id;
            }else{
                $cliente = new clientes;
                $cliente->tipoDocumento_id = $request['tipoDocumento']; 
                $cliente->documento = $request['numeroDocumento'];
                $cliente->nombre = $request['razonSocial']; 
                $cliente->save();

                $idCliente = $cliente->id;
            }
            $tipoDocumento    = tiposdocumento::find($request['tipoDocumento']);
            $venta = new ventas;
            $venta->tipoComprobante_id  = $request['tipoComprobante'];  
            $venta->cliente_id          = $idCliente;
            $venta->user_id             = auth()->id(); 
            $venta->tipoMoneda_id       = $request['tipoMoneda'];  
            $venta->numero              = $request['facturaVenta']; 
            $venta->fecha               = $request['dateFactura'];
            $venta->fechaVencimiento    = $request['dateFactura'];
            $venta->descuento           = $request['descuentoVenta']; 
            $venta->igv                 = 18;
            $venta->impuestos           = $request['igvVenta'];
            $venta->subtotal            = $request['subTotalVenta'];
            $venta->total               = $request['totalVenta'];
            $venta->estadoEmail         = false;
            $venta->estadoSunat         = false;
            $venta->observaciones       = $request['observacionVenta'];

            if($venta->save()) {
                $tiposcomprobante               = tiposcomprobante::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesventa;
                    $ventaDetalles->venta_id    = $venta->id; 
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad    = $request['cantidad'][$x];
                    $ventaDetalles->igv         = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento   = $request['descuento'][$x];
                    $ventaDetalles->subtotal    = $request['subtotal'][$x];
                    $ventaDetalles->total       = $request['total'][$x];
                    $ventaDetalles->save();

                    $producto = Productos::find($request['nombreProducto'][$x]);
                    $producto->cantidad = $producto->cantidad - $request['cantidad'][$x];

                    if($producto->update()){
                        $control = new control;
                        $control->user_id = auth()->id();
                        $control->metodo = "actualizar";
                        $control->tabla = "Productos";
                        $control->campos = "cantidad";
                        $control->datos = $request['cantidad'][$x];
                        $control->descripcion = "Actualizar la cantidad de productos despues de realizar una venta";
                        $control->save();    
                    }

                }
            }

            $codigoQr = QrCode::format('png')
                                ->size(250)
                                ->generate(
                                    "20605007211|".$tiposcomprobante->codigo."|".$request['serieVenta']."|".$request['facturaVenta']."|".$venta->impuestos."|".$venta->total."|".$request['dateFactura']."|".$tipoDocumento->codigo."|".$request['numeroDocumento']."|", public_path('img/qr.png')
                                );

            // IMPRIMIR TICKET
            $nombre_impresora = "POS"; 

            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            
            try{
                $logo = EscposImage::load(public_path('img/logo.png'), false);
                $printer->bitImage($logo);
            }catch(Exception $e){/*No hacemos nada si hay error*/}


            $printer->text("\n"."LA PRECIOSA " . "\n");
            $printer->text("Direccion: Dean Valdivia 412 A" . "\n");
            $printer->text("Tel: 054 77 34 22" . "\n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("FACTURA ELECTRONICA"."\n");
            $printer->text("SERIE: ".$request['serieVenta']."-".$request['facturaVenta']."\n");
            #La fecha también
            date_default_timezone_set("America/Mexico_City");
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            $printer->text("-----------------------------"."\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    
                    $producto = productos::where('id', $request['nombreProducto'][$x])
                                        ->first();

                    $printer->text($producto['nombre'].": \n");
                    $printer->text( $request['cantidad'][$x]."  unidad    ".$producto['precio']." ".$request['total'][$x]."   \n");
                    
                    
                }
                

            $printer->text("-----------------------------"."\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: ".$request['subTotalVenta']."\n");
            $printer->text("IVA: ".$request['igvVenta']."\n");
            $printer->text("TOTAL: ".$request['totalVenta']."\n");
            $printer->text("\n");
            
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Puede consultar en preciosaweb.com (https://preciosaweb.com/consultas)"."\n");
            $printer->text("\n");
            $printer->text("\n");
            
            $imgQr = EscposImage::load(public_path('img/qr.png'), false);
            $printer->bitImage($imgQr);
            $printer->text("Muchas gracias por su compra\n");

            $printer->feed(3);
            $printer->cut();
            $printer->pulse();
            $printer->close();

            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'setValue' => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal' => $venta->total,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    
    public function emitirBoleta(Request $request)
    {
        date_default_timezone_set("America/Mexico_City");
        // ENVIAR A LA SUNAT 
        $rucEmpresa     = "20605007211";
        $usuarioEmpresa = "CITINETY";
        $passEmpresa    = "raulpreciosajohnson";

        $see = new See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setCertificate(file_get_contents(public_path('\sunat\certificados\certificate.pem')));
        $see->setCredentials('20605007211CITINETY'/*ruc+usuario*/, 'raulpreciosajohnson');
        // ---------- FACTURACION -------------
        $tipoDocumento    = tiposdocumento::find($request['tipoDocumento']);
        $tiposcomprobante = tiposcomprobante::find($request['tipoComprobante']);
        $tipoMoneda       = tiposmoneda::find($request['tipoMoneda']);

        // Cliente
        $client = new Client();
        $client->setTipoDoc($tipoDocumento->codigo) //6 es RUC
            ->setNumDoc($request['numeroDocumento'])
            ->setRznSocial($request['nombreCliente']);

        // Emisor
        $address = new Address();
        $address->setUbigueo('040101')
            ->setDepartamento('AREQUIPA')
            ->setProvincia('AREQUIPA')
            ->setDistrito('AREQUIPA')
            ->setUrbanizacion('NONE')
            ->setDireccion('CAL. DEAN VALDIVIA 410, 412, 4 NRO. --');

        $company = new Company();
        $company->setRuc('20000000001')
                ->setRazonSocial('LA  E.I.R.L')
                ->setNombreComercial('PRECIOSA')
                ->setAddress($address);

        DB::beginTransaction();
        try {
            
            $cliente = clientes::where('documento', $request['numeroDocumento'])
                                ->first();

            if($cliente){
                $idCliente = $cliente->id;
            }else{
                $cliente = new clientes;
                $cliente->tipoDocumento_id = $request['tipoDocumento']; 
                $cliente->documento = $request['numeroDocumento'];
                $cliente->nombre = $request['nombreCliente']; 
                $cliente->save();

                $idCliente = $cliente->id;
            }

            $venta = new ventas;
            $venta->tipoComprobante_id  = $request['tipoComprobante'];  
            $venta->cliente_id          = $idCliente;
            $venta->user_id             = auth()->id(); 
            $venta->tipoMoneda_id       = $request['tipoMoneda'];  
            $venta->numero              = $request['facturaVenta']; 
            $venta->fecha               = $request['dateFactura'];
            $venta->fechaVencimiento    =  $request['dateFactura'];
            $venta->descuento           = $request['descuentoVenta']; 
            $venta->igv                 = 18;
            $venta->impuestos           = $request['igvVenta'];
            $venta->subtotal            = $request['subTotalVenta'];
            $venta->total               = $request['totalVenta'];
            $venta->estadoEmail         = false;
            $venta->estadoSunat         = true;
            $venta->observaciones       = $request['observacionVenta'];

            if($venta->save()) {
                // Venta
                $fechaActual = date('Y-m-d');
                $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Catalog. 51
                ->setTipoDoc($tiposcomprobante->codigo)
                ->setSerie($request['serieVenta']) 
                ->setCorrelativo( $request['facturaVenta'])
                ->setFechaEmision(new \DateTime(date("d-m-Y H:i:s", strtotime($request['dateFactura']))))
                ->setTipoMoneda($tipoMoneda->abreviatura)
                ->setClient($client)
                ->setMtoOperGravadas($venta->subtotal) //100
                ->setMtoIGV($venta->impuestos) //18
                ->setTotalImpuestos($venta->impuestos) //18 
                ->setValorVenta( $venta->subtotal ) //100
                ->setMtoImpVenta( $venta->total ) //118
                ->setCompany($company);
                $items = [];

                $tiposcomprobante               = tiposcomprobante::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo  = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesventa;
                    $ventaDetalles->venta_id    = $venta->id; 
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad    = $request['cantidad'][$x];
                    $ventaDetalles->igv         = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento   = $request['descuento'][$x];
                    $ventaDetalles->subtotal    = $request['subtotal'][$x];
                    $ventaDetalles->total       = $request['total'][$x];
                    $ventaDetalles->save();

                    $producto = Productos::find($request['nombreProducto'][$x]);
                    $producto->cantidad = $producto->cantidad - $request['cantidad'][$x];

                    if($producto->update()){
                        $producto = productos::find($request['nombreProducto'][$x]);
                        
                        $precioFinalProducto  = $producto->precio - $request['descuento'][$x];
                        $productoPrecioSinIgv = $precioFinalProducto/1.18;
                        $productoPrecioCantidadSinIgv   = $productoPrecioSinIgv* $request['cantidad'][$x];
                        $productoImpuesto               = $request['total'][$x] - $request['subtotal'][$x];
                        $items[$x] = (new SaleDetail())
                            ->setCodProducto($producto->codigo)
                            ->setUnidad('NIU')
                            ->setCantidad($request['cantidad'][$x])
                            ->setDescripcion($producto->nombre)
                            ->setMtoBaseIgv($productoPrecioCantidadSinIgv)
                            ->setPorcentajeIgv(18.00) // 18%
                            ->setIgv(sprintf("%.2f", $productoImpuesto))
                            ->setTipAfeIgv('10')
                            ->setTotalImpuestos(sprintf("%.2f", $productoImpuesto))
                            ->setMtoValorVenta($productoPrecioCantidadSinIgv)
                            ->setMtoValorUnitario($productoPrecioSinIgv)
                            ->setMtoPrecioUnitario($precioFinalProducto);

                        $control = new control;
                        $control->user_id = auth()->id();
                        $control->metodo = "actualizar";
                        $control->tabla = "Productos";
                        $control->campos = "cantidad";
                        $control->datos = $request['cantidad'][$x];
                        $control->descripcion = "Actualizar la cantidad de productos despues de realizar una venta con  boleta";
                        $control->save();    
                    }
                }
            }

            $legend = (new Legend())
            ->setCode('1000')
            ->setValue(NumerosEnLetras::convertir($venta->total).'/100 SOLES');

            $invoice->setDetails($items)
                    ->setLegends([$legend]);

            $result = $see->send($invoice);

            // Guardar XML
            file_put_contents(public_path('\sunat\xml\venta-'.$venta->id.'-'.$invoice->getName().'.xml'),
                                $see->getFactory()->getLastXml());
            if (!$result->isSuccess()) {
                var_dump($result->getError());
                exit();
            }

            
            // Guardar CDR
            file_put_contents(public_path('\sunat\zip\venta-'.$venta->id.'-R-'.$invoice->getName().'.zip'), $result->getCdrZip());

            $venta      = ventas::find($venta->id);
            $venta->xml = '\sunat\xmp\venta-'.$venta->id.'-'.$invoice->getName().'.xml';
            $venta->cdr = '\sunat\zip\venta-'.$venta->id.'-R-'.$invoice->getName().'.zip';
            $venta->update();

            $codigoQr = QrCode::format('png')->size(250)->generate($rucEmpresa."|".$tiposcomprobante->codigo."|".$request['serieVenta']."|".$request['facturaVenta']."|".$venta->impuestos."|".$venta->total."|".$request['dateFactura']."|".$tipoDocumento->codigo."|".$request['numeroDocumento']."|", public_path('img/qr.png'));

            // IMPRIMIR TICKET
            $nombre_impresora = "POS"; 

            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            
            try{
                $logo = EscposImage::load(public_path('img/logo.png'), false);
                $printer->bitImage($logo);
            }catch(Exception $e){/*No hacemos nada si hay error*/}


            $printer->text("\n"."LA PRECIOSA " . "\n");
            $printer->text("Direccion: Dean Valdivia 412 A" . "\n");
            $printer->text("Tel: 054 77 34 22" . "\n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("BOLETA ELECTRONICA"."\n");
            $printer->text("SERIE: ".$request['serieVenta']."-".$request['facturaVenta']."\n");
            #La fecha también
            
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            $printer->text("-----------------------------"."\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    
                    $producto = productos::where('id', $request['nombreProducto'][$x])
                                        ->first();

                    $printer->text($producto['nombre'].": \n");
                    $printer->text( $request['cantidad'][$x]."  unidad    ".$producto['precio']." ".$request['total'][$x]."   \n");
                    
                    
                }
                

            $printer->text("-----------------------------"."\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: ".$request['subTotalVenta']."\n");
            $printer->text("IVA: ".$request['igvVenta']."\n");
            $printer->text("TOTAL: ".$request['totalVenta']."\n");
            $printer->text("\n");
            
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Puede consultar en preciosaweb.com (https://preciosaweb.com/consultas)"."\n");
            $printer->text("\n");
            $printer->text("\n");
            $imgQr = EscposImage::load(public_path('img/qr.png'), false);
            $printer->bitImage($imgQr);
            $printer->text("Muchas gracias por su compra\n");

            $printer->feed(3);
            $printer->cut();
            $printer->pulse();
            $printer->close();

            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'respuestaSunat' => $result->getCdrResponse()->getDescription(),
                'setValue' => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal' => $venta->total,
                'invoice' => $invoice
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    
    public function guardarEmitirBoleta(Request $request)
    {
        DB::beginTransaction();
        try {
            $correlativo = ventas::where('numero', $request['facturaVenta'])
                                ->where('tipoComprobante_id', $request['tipoComprobante'])
                                ->first();

            if($correlativo){
                
                $rpta = array(
                    'response'      =>  false,
                );
                
                return json_encode($rpta);
            }

            $cliente = clientes::where('documento', $request['numeroDocumento'])
                            ->first();
            
            if($cliente){
                $idCliente = $cliente->id;
            }else{
                $cliente = new clientes;
                $cliente->tipoDocumento_id = $request['tipoDocumento']; 
                $cliente->documento = $request['numeroDocumento'];
                $cliente->nombre = $request['nombreCliente']; 
                $cliente->save();
                
                $idCliente = $cliente->id;
            }
            $tipoDocumento    = tiposdocumento::find($request['tipoDocumento']);
            $venta = new ventas;
            $venta->tipoComprobante_id  = $request['tipoComprobante'];  
            $venta->cliente_id          = $idCliente;
            $venta->user_id             = auth()->id(); 
            $venta->tipoMoneda_id       = $request['tipoMoneda'];  
            $venta->numero              = $request['facturaVenta']; 
            $venta->fecha               = $request['dateFactura'];
            $venta->fechaVencimiento    =  $request['dateFactura'];
            $venta->descuento           = $request['descuentoVenta']; 
            $venta->igv                 = 18;
            $venta->impuestos           = $request['igvVenta'];
            $venta->subtotal            = $request['subTotalVenta'];
            $venta->total               = $request['totalVenta'];
            $venta->estadoEmail         = false;
            $venta->estadoSunat         = false;
            $venta->observaciones       = $request['observacionVenta'];

            if($venta->save()) {
                $tiposcomprobante = tiposcomprobante::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles              = new detallesventa;
                    $ventaDetalles->venta_id    = $venta->id; 
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad    = $request['cantidad'][$x];
                    $ventaDetalles->igv         = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento   = $request['descuento'][$x];
                    $ventaDetalles->subtotal    = $request['subtotal'][$x];
                    $ventaDetalles->total       = $request['total'][$x];
                    $ventaDetalles->save();

                    $producto = Productos::find($request['nombreProducto'][$x]);
                    $producto->cantidad = $producto->cantidad - $request['cantidad'][$x];
                    if($producto->update()){
                        $control = new control;
                        $control->user_id = auth()->id();
                        $control->metodo = "actualizar";
                        $control->tabla = "Productos";
                        $control->campos = "cantidad";
                        $control->datos = $request['cantidad'][$x];
                        $control->descripcion = "Actualizar la cantidad de productos despues de realizar una venta";
                        $control->save();    
                    }
                }
            }

            $codigoQr = QrCode::format('png')
                                ->size(250)
                                ->generate(
                                    "20605007211|".$tiposcomprobante->codigo."|".$request['serieVenta']."|".$request['facturaVenta']."|".$venta->impuestos."|".$venta->total."|".$request['dateFactura']."|".$tipoDocumento->codigo."|".$request['numeroDocumento']."|", public_path('img/qr.png')
                                );

            // IMPRIMIR TICKET
            $nombre_impresora = "POS"; 

            $connector = new WindowsPrintConnector($nombre_impresora);
            $printer = new Printer($connector);

            $printer->setJustification(Printer::JUSTIFY_CENTER);

            try{
                $logo = EscposImage::load(public_path('img/logo.png'), false);
                $printer->bitImage($logo);
            }catch(Exception $e){/*No hacemos nada si hay error*/}

            $printer->text("\n"."LA PRECIOSA " . "\n");
            $printer->text("Direccion: Dean Valdivia 412 A" . "\n");
            $printer->text("Tel: 054 77 34 22" . "\n");
            $printer->text("\n");
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("BOLETA ELECTRONICA"."\n");
            $printer->text("SERIE: ".$request['serieVenta']."-".$request['facturaVenta']."\n");
            #La fecha también
            date_default_timezone_set("America/Mexico_City");
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            $printer->text("-----------------------------"."\n");

                $printer->setJustification(Printer::JUSTIFY_LEFT);
                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    
                    $producto = productos::where('id', $request['nombreProducto'][$x])
                                        ->first();

                    $printer->text($producto['nombre'].": \n");
                    $printer->text( $request['cantidad'][$x]."  unidad    ".$producto['precio']." ".$request['total'][$x]."   \n");
                    
                    
                }
                

            $printer->text("-----------------------------"."\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: ".$request['subTotalVenta']."\n");
            $printer->text("IVA: ".$request['igvVenta']."\n");
            $printer->text("TOTAL: ".$request['totalVenta']."\n");
            
            $printer->text("\n");
            
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Puede consultar en preciosaweb.com (https://preciosaweb.com/consultas)"."\n");
            $printer->text("\n");
            $printer->text("\n");

            $imgQr = EscposImage::load(public_path('img/qr.png'), false);
            $printer->bitImage($imgQr);
            $printer->text("Muchas gracias por su compra\n");

            $printer->feed(3);
            $printer->cut();
            $printer->pulse();
            $printer->close();

            DB::commit();

            $rpta = array(
                'response'      =>  true,
                'setValue' => NumerosEnLetras::convertir($venta->total).'/100 SOLES',
                'ventaTotal' => $venta->total,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    } 

    public function comprobanteEmitir(Request $request)
    {
        
        
        
        $ventas = ventas::select(   'td.codigo as codigoTiposdocumento', 'c.documento as documentoClientes',
                                    'c.nombre as nombreClientes', 'tc.codigo as codigoTiposcomprobante',
                                    'tc.serie as serieTiposcomprobante', 'ventas.numero as numeorVentas', 
                                    'ventas.fecha as fechaVentas', 'tm.abreviatura as abreviaturaTiposmoneda',
                                    'ventas.subtotal as subtotalVentas', 'ventas.impuestos as impuestosVentas',
                                    'ventas.total as totalVentas', 'ventas.id as idVentas'
                                )
                        ->join('clientes as c', 'ventas.cliente_id', '=', 'c.id')            
                        ->join('tiposdocumento as td', 'c.tipoDocumento_id', '=', 'td.id')
                        ->join('tiposcomprobante as tc', 'ventas.tipoComprobante_id', '=', 'tc.id')
                        ->join('tiposmoneda as tm', 'ventas.tipoMoneda_id', '=', 'tm.id')
                        ->where('ventas.id', '=', $request['id'])
                        ->first();


        echo $ventas;
    }

    public function productoTemporalCrear(Request $request)
    {
        DB::beginTransaction();
        try {
            if($request['codigoProductoNuevo'] == null){
                $codigo = "TEMPORAL";
            }else{
                $codigo = $request['codigoProductoNuevo'];
            }


            $productos = new Productos;
            $productos->codigo = $codigo;
            $productos->marca_id = 100;
            $productos->tipo_id = 1000;
            $productos->nombre = $request['nombreProductoNuevo'];
            $productos->cantidad = 0;
            $productos->precio = $request['precioVentaProducto'];
            $productos->precioVista = $request['precioVentaProducto'];
            
            if($productos->save()) {
                $control = new control;
                $control->user_id = auth()->id();
                $control->metodo = "crear";
                $control->tabla = "productos";
                $control->campos = "all";
                $control->datos = $request['codigoProductoNuevo'].', '. $request['nombreProductoNuevo'].', '. $request['precioVentaProducto'];
                $control->descripcion = "Crear un nuevo producto con cantidad 0";
                $control->save();
            }

            DB::commit();

            $rpta = array(
                'response'          =>  true,
                'idProducto'        =>  $productos->id,
                'nombreProducto'    =>  $productos->nombre,
                'codigoProducto'    =>  $productos->codigo,
                'precioProducto'    =>  $productos->precio,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }

    public function verDetalleVenta($idVenta)
    {

        $venta   = ventas::find($idVenta);
        $usuario = User::find($venta->user_id);
        $cliente = clientes::find($venta->cliente_id);

        $detallesVenta = detallesventa::join('productos as p', 'p.id', '=', 'detalles_venta.producto_id')
                                    ->where('venta_id', $idVenta)
                                    ->get([
                                        'p.nombre                   as nombreProducto',
                                        'p.precioVista              as precioProducto',
                                        'detalles_venta.cantidad    as cantidadProducto',
                                        'detalles_venta.igv         as igvProducto',
                                        'detalles_venta.descuento   as descuentoProducto',
                                        'detalles_venta.subtotal    as subtotalProducto',
                                        'detalles_venta.total       as totalProducto'
                                    ]);
        
        $data = array(
            'item'  =>  1,
            "cliente" => $cliente,
            "usuario" => $usuario,
            'venta'         => $venta,
            'detallesVenta' => $detallesVenta,
        );

        $pdf = PDF::loadView('ventas.pdf.detalleVentaPdf', $data)->setPaper('a4');
        return $pdf->stream();

    }

    public function descargarXml($idVenta)
    {
           
        // Guardar CDR
        // file_put_contents(public_path('\sunat\zip\venta-'.$ventas->idVentas.'-R-'.$invoice->getName().'.zip'), $result->getCdrZip());
        
        $venta = ventas::find($idVenta);
        $file   =   public_path($venta->xml);
        return Response::download($file);

    }

    

}
