<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tiposcomprobante;

class tiposcomprobanteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function index()
    {
        $tiposcomprobante = tiposcomprobante::all();
        return $tiposcomprobante;        
    }

    public static function factura()
    {
        $tiposcomprobante = tiposcomprobante::where('codigo', '01')->first();
        return $tiposcomprobante;        
    }

    public static function boleta()
    {
        $tiposcomprobante = tiposcomprobante::where('codigo', '03')->first();
        return $tiposcomprobante;        
    }

    public function facturaReact(){
        $tiposcomprobante = tiposcomprobante::where('codigo', '01')->first();
        return $tiposcomprobante; 
    }
    public function boletaReact(){
        $tiposcomprobante = tiposcomprobante::where('codigo', '03')->first();
        return $tiposcomprobante; 
    }


}
