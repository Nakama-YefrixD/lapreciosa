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


class ventasController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
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

    public function emitirFactura(Request $request)
    {
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
                // $cliente->telefono = ;
                // $cliente->direccion = ;
                // $cliente->email = ;
                $cliente->save();
                $idCliente = $cliente->id;
            }

            $venta = new ventas;
            $venta->tipoComprobante_id = $request['tipoComprobante'];  
            $venta->cliente_id = $idCliente;
            $venta->user_id = auth()->id(); 
            $venta->tipoMoneda_id = $request['tipoMoneda'];  
            $venta->numero = $request['facturaVenta']; 
            $venta->fecha = $request['dateFactura'];
            $venta->fechaVencimiento =  $request['dateFactura'];
            $venta->descuento = $request['descuentoVenta']; 
            $venta->igv = 18;
            $venta->impuestos = $request['igvVenta'];
            $venta->subtotal = $request['subTotalVenta'];
            $venta->total = $request['totalVenta'];
            $venta->estadoEmail = false;
            $venta->estadoSunat = true;
            $venta->observaciones = $request['observacionVenta'];

            if($venta->save()) {
                $tiposcomprobante = tiposcomprobante::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesventa;
                    $ventaDetalles->venta_id = $venta->id; 
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad = $request['cantidad'][$x];
                    $ventaDetalles->igv = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento = $request['descuento'][$x];
                    $ventaDetalles->subtotal = $request['subtotal'][$x];
                    $ventaDetalles->total = $request['total'][$x];
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

            


            // ENVIAR A LA SUNAT 

            $see = new See();
            $see->setService(SunatEndpoints::FE_BETA);
            $see->setCertificate(file_get_contents(public_path('\sunat\certificados\certificate.pem')));
            $see->setCredentials('20000000001MODDATOS'/*ruc+usuario*/, 'moddatos');

            

            // ---------- FACTURACION -------------
            $tipoDocumento = tiposdocumento::where('id', $request['tipoDocumento'])
                                            ->first();

            $tiposcomprobante = tiposcomprobante::where('id', $request['tipoComprobante'])
                                                ->first();

            $tipoMoneda = tiposmoneda::where('id', $request['tipoMoneda'])
                                        ->first();

            // Cliente
            $client = new Client();
            $client->setTipoDoc($tipoDocumento->codigo) //6 es RUC
                ->setNumDoc($request['numeroDocumento'])
                ->setRznSocial($request['razonSocial']);

            // Emisor
            $address = new Address();
            $address->setUbigueo('150101')
                ->setDepartamento('AREQUIPA')
                ->setProvincia('AREQUIPA')
                ->setDistrito('AREQUIPA')
                ->setUrbanizacion('NONE')
                ->setDireccion('AV LS');

            $company = new Company();
            $company->setRuc('20000000001')
                    ->setRazonSocial('EMPRESA SAC')
                    ->setNombreComercial('EMPRESA')
                    ->setAddress($address);

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
            ->setValorVenta( $venta->subtotal) //100
            ->setMtoImpVenta($venta->total) //118
            ->setCompany($company);


            $items = [];
            for ($x = 0; $x < count($request['cantidad']); $x++) {
                

                $producto = productos::where('id', $request['nombreProducto'][$x])
                                        ->first();
                $productoImpuesto = $request['total'][$x] - $request['subtotal'][$x];
                $productoPrecioSinIgv = $producto->precio * 18;
                $productoPrecioSinIgv = $productoPrecioSinIgv/100;
                $productoPrecioSinIgv = $producto->precio - $productoPrecioSinIgv;
                $productoPrecioCantidadSinIgv = $productoPrecioSinIgv * $request['cantidad'][$x];
                $items[$x] = (new SaleDetail())
                    ->setCodProducto($producto->codigo)
                    ->setUnidad('NIU')
                    ->setCantidad($request['cantidad'][$x])
                    ->setDescripcion($producto->nombre)
                    ->setMtoBaseIgv($productoPrecioCantidadSinIgv)
                    ->setPorcentajeIgv(18.00) // 18%
                    ->setIgv($productoImpuesto)
                    ->setTipAfeIgv('10')
                    ->setTotalImpuestos($productoImpuesto)
                    ->setMtoValorVenta($productoPrecioCantidadSinIgv)
                    ->setMtoValorUnitario($productoPrecioSinIgv)
                    ->setMtoPrecioUnitario($producto->precio);
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




            // IMPRIMIR TICKET
            // $nombre_impresora = "POS"; 

            // $connector = new WindowsPrintConnector($nombre_impresora);
            // $printer = new Printer($connector);

            // $printer->setJustification(Printer::JUSTIFY_CENTER);

            
            // try{
            //     $logo = EscposImage::load(public_path('img/logo.png'), false);
            //     $printer->bitImage($logo);
            // }catch(Exception $e){/*No hacemos nada si hay error*/}


            // $printer->text("\n"."LA PRECIOSA " . "\n");
            // $printer->text("Direccion: Orquídeas #151" . "\n");
            // $printer->text("Tel: 454664544" . "\n");
            // #La fecha también
            // date_default_timezone_set("America/Mexico_City");
            // $printer->text(date("Y-m-d H:i:s") . "\n");
            // $printer->text("-----------------------------" . "\n");
            // $printer->setJustification(Printer::JUSTIFY_LEFT);
            // $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            // $printer->text("-----------------------------"."\n");

            //     $printer->setJustification(Printer::JUSTIFY_LEFT);
            //     for ($x = 0; $x < count($request['cantidad']); $x++) {
                    
            //         $producto = productos::where('id', $request['nombreProducto'][$x])
            //                             ->first();

            //         $printer->text($producto['nombre'].": \n");
            //         $printer->text( $request['cantidad'][$x]."  unidad    ".$producto['precio']." ".$request['total'][$x]."   \n");
                    
                    
            //     }
                

            // $printer->text("-----------------------------"."\n");
            // $printer->setJustification(Printer::JUSTIFY_RIGHT);
            // $printer->text("SUBTOTAL: ".$request['subTotalVenta']."\n");
            // $printer->text("IVA: ".$request['igvVenta']."\n");
            // $printer->text("TOTAL: ".$request['totalVenta']."\n");

            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->text("Muchas gracias por su compra\n");

            // // try{
            // //     $logo = EscposImage::load(public_path('img/logo.png'), false);
            // //     $printer->bitImage($logo);
            // // }catch(Exception $e){/*No hacemos nada si hay error*/}

            // $printer->feed(3);
            // $printer->cut();
            // $printer->pulse();
            // $printer->close();



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
                // $cliente->telefono = ;
                // $cliente->direccion = ;
                // $cliente->email = ;
                $cliente->save();
                $idCliente = $cliente->id;
            }

            $venta = new ventas;
            $venta->tipoComprobante_id = $request['tipoComprobante'];  
            $venta->cliente_id = $idCliente;
            $venta->user_id = auth()->id(); 
            $venta->tipoMoneda_id = $request['tipoMoneda'];  
            $venta->numero = $request['facturaVenta']; 
            $venta->fecha = $request['dateFactura'];
            $venta->fechaVencimiento =  $request['dateFactura'];
            $venta->descuento = $request['descuentoVenta']; 
            $venta->igv = 18;
            $venta->impuestos = $request['igvVenta'];
            $venta->subtotal = $request['subTotalVenta'];
            $venta->total = $request['totalVenta'];
            $venta->estadoEmail = false;
            $venta->estadoSunat = false;
            $venta->observaciones = $request['observacionVenta'];

            if($venta->save()) {
                $tiposcomprobante = tiposcomprobante::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesventa;
                    $ventaDetalles->venta_id = $venta->id; 
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad = $request['cantidad'][$x];
                    $ventaDetalles->igv = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento = $request['descuento'][$x];
                    $ventaDetalles->subtotal = $request['subtotal'][$x];
                    $ventaDetalles->total = $request['total'][$x];
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
            $printer->text("Direccion: Orquídeas #151" . "\n");
            $printer->text("Tel: 454664544" . "\n");
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

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Muchas gracias por su compra\n");

            // try{
            //     $logo = EscposImage::load(public_path('img/logo.png'), false);
            //     $printer->bitImage($logo);
            // }catch(Exception $e){/*No hacemos nada si hay error*/}

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
                // $cliente->telefono = ;
                // $cliente->direccion = ;
                // $cliente->email = ;
                $cliente->save();
                $idCliente = $cliente->id;
            }

            $venta = new ventas;
            $venta->tipoComprobante_id = $request['tipoComprobante'];  
            $venta->cliente_id = $idCliente;
            $venta->user_id = auth()->id(); 
            $venta->tipoMoneda_id = $request['tipoMoneda'];  
            $venta->numero = $request['facturaVenta']; 
            $venta->fecha = $request['dateFactura'];
            $venta->fechaVencimiento =  $request['dateFactura'];
            $venta->descuento = $request['descuentoVenta']; 
            $venta->igv = 18;
            $venta->impuestos = $request['igvVenta'];
            $venta->subtotal = $request['subTotalVenta'];
            $venta->total = $request['totalVenta'];
            $venta->estadoEmail = false;
            $venta->estadoSunat = true;
            $venta->observaciones = $request['observacionVenta'];

            if($venta->save()) {
                $tiposcomprobante = tiposcomprobante::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesventa;
                    $ventaDetalles->venta_id = $venta->id; 
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad = $request['cantidad'][$x];
                    $ventaDetalles->igv = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento = $request['descuento'][$x];
                    $ventaDetalles->subtotal = $request['subtotal'][$x];
                    $ventaDetalles->total = $request['total'][$x];
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
                        $control->descripcion = "Actualizar la cantidad de productos despues de realizar una venta con  boleta";
                        $control->save();    
                    }
                }
            }

            


            // ENVIAR A LA SUNAT 

            $see = new See();
            $see->setService(SunatEndpoints::FE_BETA);
            $see->setCertificate(file_get_contents(public_path('\sunat\certificados\certificate.pem')));
            $see->setCredentials('20000000001MODDATOS'/*ruc+usuario*/, 'moddatos');

            // ---------- FACTURACION -------------
            $tipoDocumento = tiposdocumento::where('id', $request['tipoDocumento'])
                                            ->first();

            $tiposcomprobante = tiposcomprobante::where('id', $request['tipoComprobante'])
                                                ->first();

            $tipoMoneda = tiposmoneda::where('id', $request['tipoMoneda'])
                                        ->first();

            // Cliente
            $client = new Client();
            $client->setTipoDoc($tipoDocumento->codigo) //6 es RUC
                ->setNumDoc($request['numeroDocumento'])
                ->setRznSocial($request['nombreCliente']);

            // Emisor
            $address = new Address();
            $address->setUbigueo('150101')
                ->setDepartamento('AREQUIPA')
                ->setProvincia('AREQUIPA')
                ->setDistrito('AREQUIPA')
                ->setUrbanizacion('NONE')
                ->setDireccion('AV LS');

            $company = new Company();
            $company->setRuc('20000000001')
                    ->setRazonSocial('EMPRESA SAC')
                    ->setNombreComercial('EMPRESA')
                    ->setAddress($address);

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
            ->setValorVenta( $venta->subtotal) //100
            ->setMtoImpVenta($venta->total) //118
            ->setCompany($company);


            $items = [];
            for ($x = 0; $x < count($request['cantidad']); $x++) {
                
                $producto = productos::where('id', $request['nombreProducto'][$x])
                                        ->first();
                $productoImpuesto = $request['total'][$x] - $request['subtotal'][$x];
                $productoPrecioSinIgv = $producto->precio * 18;
                $productoPrecioSinIgv = $productoPrecioSinIgv/100;
                $productoPrecioSinIgv = $producto->precio - $productoPrecioSinIgv;
                $productoPrecioCantidadSinIgv = $productoPrecioSinIgv * $request['cantidad'][$x];
                $items[$x] = (new SaleDetail())
                    ->setCodProducto($producto->codigo)
                    ->setUnidad('NIU')
                    ->setCantidad($request['cantidad'][$x])
                    ->setDescripcion($producto->nombre)
                    ->setMtoBaseIgv($productoPrecioCantidadSinIgv)
                    ->setPorcentajeIgv(18.00) // 18%
                    ->setIgv($productoImpuesto)
                    ->setTipAfeIgv('10')
                    ->setTotalImpuestos($productoImpuesto)
                    ->setMtoValorVenta($productoPrecioCantidadSinIgv)
                    ->setMtoValorUnitario($productoPrecioSinIgv)
                    ->setMtoPrecioUnitario($producto->precio);
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




            // IMPRIMIR TICKET
            // $nombre_impresora = "POS"; 

            // $connector = new WindowsPrintConnector($nombre_impresora);
            // $printer = new Printer($connector);

            // $printer->setJustification(Printer::JUSTIFY_CENTER);

            
            // try{
            //     $logo = EscposImage::load(public_path('img/logo.png'), false);
            //     $printer->bitImage($logo);
            // }catch(Exception $e){/*No hacemos nada si hay error*/}


            // $printer->text("\n"."LA PRECIOSA " . "\n");
            // $printer->text("Direccion: Orquídeas #151" . "\n");
            // $printer->text("Tel: 454664544" . "\n");
            // #La fecha también
            // date_default_timezone_set("America/Mexico_City");
            // $printer->text(date("Y-m-d H:i:s") . "\n");
            // $printer->text("-----------------------------" . "\n");
            // $printer->setJustification(Printer::JUSTIFY_LEFT);
            // $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            // $printer->text("-----------------------------"."\n");

            //     $printer->setJustification(Printer::JUSTIFY_LEFT);
            //     for ($x = 0; $x < count($request['cantidad']); $x++) {
                    
            //         $producto = productos::where('id', $request['nombreProducto'][$x])
            //                             ->first();

            //         $printer->text($producto['nombre'].": \n");
            //         $printer->text( $request['cantidad'][$x]."  unidad    ".$producto['precio']." ".$request['total'][$x]."   \n");
                    
                    
            //     }
                

            // $printer->text("-----------------------------"."\n");
            // $printer->setJustification(Printer::JUSTIFY_RIGHT);
            // $printer->text("SUBTOTAL: ".$request['subTotalVenta']."\n");
            // $printer->text("IVA: ".$request['igvVenta']."\n");
            // $printer->text("TOTAL: ".$request['totalVenta']."\n");

            // $printer->setJustification(Printer::JUSTIFY_CENTER);
            // $printer->text("Muchas gracias por su compra\n");

            // // try{
            // //     $logo = EscposImage::load(public_path('img/logo.png'), false);
            // //     $printer->bitImage($logo);
            // // }catch(Exception $e){/*No hacemos nada si hay error*/}

            // $printer->feed(3);
            // $printer->cut();
            // $printer->pulse();
            // $printer->close();



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
                // $cliente->telefono = ;
                // $cliente->direccion = ;
                // $cliente->email = ;
                $cliente->save();
                $idCliente = $cliente->id;
            }

            $venta = new ventas;
            $venta->tipoComprobante_id = $request['tipoComprobante'];  
            $venta->cliente_id = $idCliente;
            $venta->user_id = auth()->id(); 
            $venta->tipoMoneda_id = $request['tipoMoneda'];  
            $venta->numero = $request['facturaVenta']; 
            $venta->fecha = $request['dateFactura'];
            $venta->fechaVencimiento =  $request['dateFactura'];
            $venta->descuento = $request['descuentoVenta']; 
            $venta->igv = 18;
            $venta->impuestos = $request['igvVenta'];
            $venta->subtotal = $request['subTotalVenta'];
            $venta->total = $request['totalVenta'];
            $venta->estadoEmail = false;
            $venta->estadoSunat = false;
            $venta->observaciones = $request['observacionVenta'];

            if($venta->save()) {
                $tiposcomprobante = tiposcomprobante::find($request['tipoComprobante']);
                $tiposcomprobante->correlativo = $request['facturaVenta']+1;
                $tiposcomprobante->update();

                for ($x = 0; $x < count($request['cantidad']); $x++) {
                    $ventaDetalles = new detallesventa;
                    $ventaDetalles->venta_id = $venta->id; 
                    $ventaDetalles->producto_id = $request['nombreProducto'][$x];
                    $ventaDetalles->cantidad = $request['cantidad'][$x];
                    $ventaDetalles->igv = $request['total'][$x] - $request['subtotal'][$x];
                    $ventaDetalles->descuento = $request['descuento'][$x];
                    $ventaDetalles->subtotal = $request['subtotal'][$x];
                    $ventaDetalles->total = $request['total'][$x];
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
            $printer->text("Direccion: Orquídeas #151" . "\n");
            $printer->text("Tel: 454664544" . "\n");
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

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Muchas gracias por su compra\n");

            // try{
            //     $logo = EscposImage::load(public_path('img/logo.png'), false);
            //     $printer->bitImage($logo);
            // }catch(Exception $e){/*No hacemos nada si hay error*/}

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
        DB::beginTransaction();
        try {
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


        $see = new See();
        $see->setService(SunatEndpoints::FE_BETA);
        $see->setCertificate(file_get_contents(public_path('\sunat\certificados\certificate.pem')));
        $see->setCredentials('20000000001MODDATOS'/*ruc+usuario*/, 'moddatos');


        // Cliente
        $client = new Client();
        $client->setTipoDoc($ventas->codigoTiposdocumento) //6 es RUC
            ->setNumDoc($ventas->documentoClientes)
            ->setRznSocial($ventas->nombreClientes);

        // Emisor
        $address = new Address();
        $address->setUbigueo('150101')
            ->setDepartamento('AREQUIPA')
            ->setProvincia('AREQUIPA')
            ->setDistrito('AREQUIPA')
            ->setUrbanizacion('NONE')
            ->setDireccion('AV LS');

        $company = new Company();
        $company->setRuc('20000000001')
                ->setRazonSocial('EMPRESA SAC')
                ->setNombreComercial('EMPRESA')
                ->setAddress($address);

        // Venta
        $fechaActual = date('Y-m-d');
        $invoice = (new Invoice())
        ->setUblVersion('2.1')
        ->setTipoOperacion('0101') // Catalog. 51
        ->setTipoDoc($ventas->codigoTiposcomprobante)
        ->setSerie($ventas->serieTiposcomprobante) 
        ->setCorrelativo($ventas->numeorVentas)
        ->setFechaEmision(new \DateTime(date("d-m-Y H:i:s", strtotime($ventas->fechaVentas))))
        ->setTipoMoneda($ventas->abreviaturaTiposmoneda)
        ->setClient($client)
        ->setMtoOperGravadas($ventas->subtotalVentas) //100
        ->setMtoIGV($ventas->impuestosVentas) //18
        ->setTotalImpuestos($ventas->impuestosVentas) //18
        ->setValorVenta( $ventas->subtotalVentas) //100
        ->setMtoImpVenta($ventas->totalVentas) //118
        ->setCompany($company);


        $items = [];

        $ventaDetalles = detallesventa::where('venta_id', $ventas->idVentas)
                                        ->get();
        $x = 0;
        foreach($ventaDetalles as $ventaDetalle){
            
            $producto = productos::where('id', $ventaDetalle->producto_id )
                                    ->first();

            $productoImpuesto = $ventaDetalle->total - $ventaDetalle->subtotal;
            $productoPrecioSinIgv = $producto->precio * 18;
            $productoPrecioSinIgv = $productoPrecioSinIgv/100;
            $productoPrecioSinIgv = $producto->precio - $productoPrecioSinIgv;
            $productoPrecioCantidadSinIgv = $productoPrecioSinIgv * $ventaDetalle->cantidad;
            $items[$x] = (new SaleDetail())
                ->setCodProducto($producto->codigo)
                ->setUnidad('NIU')
                ->setCantidad($ventaDetalle->cantidad)
                ->setDescripcion($producto->nombre)
                ->setMtoBaseIgv($productoPrecioCantidadSinIgv)
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv($productoImpuesto)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos($productoImpuesto)
                ->setMtoValorVenta($productoPrecioCantidadSinIgv)
                ->setMtoValorUnitario($productoPrecioSinIgv)
                ->setMtoPrecioUnitario($producto->precio);
            $x = $x+1;
        }

        $legend = (new Legend())
        ->setCode('1000')
        ->setValue(NumerosEnLetras::convertir($ventas->totalVentas).'/100 SOLES');

        $invoice->setDetails($items)
                ->setLegends([$legend]);

        $result = $see->send($invoice);

        // Guardar XML
        file_put_contents(public_path('\sunat\xml\venta-'.$ventas->idVentas.'-'.$invoice->getName().'.xml'),
                            $see->getFactory()->getLastXml());
        if (!$result->isSuccess()) {
            var_dump($result->getError());
            exit();
        }else{
            $venta = ventas::find($ventas->idVentas);
            $venta->estadoSunat = 1;
            $venta->update();
        }

        
        // Guardar CDR
        file_put_contents(public_path('\sunat\zip\venta-'.$ventas->idVentas.'-R-'.$invoice->getName().'.zip'), $result->getCdrZip());
        DB::commit();

            $rpta = array(
                'response'      =>  true,
                'setValue' => NumerosEnLetras::convertir($ventas->total).'/100 SOLES',
                'ventaTotal' => $ventas->total,
            );
            echo json_encode($rpta);
        } catch (\Exception $e) {
            DB::rollBack();
            echo json_encode($e->getMessage());
        }
    }
}
