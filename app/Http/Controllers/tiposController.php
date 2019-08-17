<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tipos;

class tiposController extends Controller
{
    public static function tiposGet()
    {
        $tipos = Tipos::all();
        return $tipos;        
    }
}
