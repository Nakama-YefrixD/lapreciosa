<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\tiposMoneda;

class TiposMonedaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function index()
    {
        $tiposMoneda = tiposMoneda::all();
        return $tiposMoneda;        
    }
}
