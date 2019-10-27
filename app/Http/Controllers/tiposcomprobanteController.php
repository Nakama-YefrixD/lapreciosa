<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tiposComprobante;

class tiposcomprobanteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function index()
    {
        $tiposcomprobante = tiposComprobante::all();
        return $tiposcomprobante;        
    }

    public static function factura()
    {
        $tiposcomprobante = tiposComprobante::where('codigo', '01')->first();
        return $tiposcomprobante;        
    }

    public static function boleta()
    {
        $tiposcomprobante = tiposComprobante::where('codigo', '03')->first();
        return $tiposcomprobante;        
    }

    public function facturaReact(){
        $tiposcomprobante = tiposComprobante::where('codigo', '01')->first();
        return $tiposcomprobante; 
    }
    public function boletaReact(){
        $tiposcomprobante = tiposComprobante::where('codigo', '03')->first();
        return $tiposcomprobante; 
    }


}
