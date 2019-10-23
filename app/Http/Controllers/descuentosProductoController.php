<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\descuentosProducto;

class descuentosProductoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public static function index()
    {
        $descuentosProducto = descuentosProducto::all();
        return $descuentosProducto;        
    }
}
