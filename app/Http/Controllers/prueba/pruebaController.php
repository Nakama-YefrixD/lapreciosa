<?php

namespace App\Http\Controllers\prueba;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use QrCode;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

class pruebaController extends Controller
{
    public function index()
    {
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
            #La fecha también
            
            $printer->text(date("Y-m-d H:i:s") . "\n");
            $printer->text("-----------------------------" . "\n");
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("CANT  DESCRIPCION    P.U   IMP.\n");
            $printer->text("-----------------------------"."\n");

            $printer->setJustification(Printer::JUSTIFY_LEFT);
                
                

            $printer->text("-----------------------------"."\n");
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("SUBTOTAL: 2 \n");
            $printer->text("IVA: 2\n");
            $printer->text("TOTAL: 3\n");

            $printer->setJustification(Printer::JUSTIFY_CENTER);
            
            $printer->text(QrCode::size(500)->generate('hola'));
            $printer->text("Muchas gracias por su compra\n");

            $printer->feed(3);
            $printer->cut();
            $printer->pulse();
            $printer->close();


            $codigoqr = QrCode::size(500)->generate('hola');
            
        return $codigoqr;
    }
}
