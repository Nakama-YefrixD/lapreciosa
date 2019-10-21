<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\productos;
use App\marcas;
use App\tipos;

class agregarProductoController extends Controller
{
    public function buscarCodigo(Request $request)
    {
        $productos = productos::join('marcas as m', 'm.id', '=', 'productos.marca_id')
                                ->join('tipos as t', 't.id', '=', 'productos.tipo_id')
                                ->where(function ($query) use($request) {
                                    if($request['texto'] != ''){
                                            $query->where('productos.codigo', 'like', '%' . $request['texto'] . '%');
                                        }
                                    })
                                ->limit(5)
                                ->get([
                                    'productos.id     as idProducto',
                                    'productos.nombre as nombreProducto',
                                    'productos.codigo as codigoProducto',
                                    'm.id             as idMarca',
                                    't.id             as idTipo',
                                    'm.nombre         as nombreMarca',
                                    't.nombre         as nombreTipo',
                                    'productos.precio as precioProducto'

                                ]);

        if (sizeof($productos) > 0){
            return json_encode(
                array(
                    "code" => true, 
                    "result"=>$productos
                    )
            );
        }else{
            return json_encode(
                array(
                    "code" => false, 
                    )
            );
        }

    }

    public function buscarNombre(Request $request)
    {
        $productos = productos::join('marcas as m', 'm.id', '=', 'productos.marca_id')
                                ->join('tipos as t', 't.id', '=', 'productos.tipo_id')
                                ->where(function ($query) use($request) {
                                    if($request['texto'] != ''){
                                            $query->where('productos.nombre', 'like', '%' . $request['texto'] . '%');
                                        }
                                    })
                                ->limit(5)
                                ->get([
                                    'productos.id     as idProducto',
                                    'productos.nombre as nombreProducto',
                                    'productos.codigo as codigoProducto',
                                    'm.id             as idMarca',
                                    't.id             as idTipo',
                                    'm.nombre         as nombreMarca',
                                    't.nombre         as nombreTipo',
                                    'productos.precio as precioProducto'

                                ]);

        if (sizeof($productos) > 0){
            return json_encode(
                array(
                    "code" => true, 
                    "result"=>$productos
                    )
            );
        }else{
            return json_encode(
                array(
                    "code" => false, 
                    )
            );
        }

    }

    public function buscarMarca(Request $request)
    {
        $marcas = marcas::where(function ($query) use($request) {
                                    if($request['texto'] != ''){
                                            $query->where('nombre', 'like', '%' . $request['texto'] . '%');
                                        }
                                    })
                                ->limit(5)
                                ->get([
                                    'marcas.id      as idMarca',
                                    'marcas.nombre  as nombreMarca'
                                ]);

        if (sizeof($marcas) > 0){
            return json_encode(
                array(
                    "code" => true, 
                    "result"=>$marcas
                    )
            );
        }else{
            return json_encode(
                array(
                    "code" => false, 
                    )
            );
        }

    }

    public function buscarTipos(Request $request)
    {
        $tipos = tipos::where(function ($query) use($request) {
                                    if($request['texto'] != ''){
                                            $query->where('nombre', 'like', '%' . $request['texto'] . '%');
                                        }
                                    })
                                ->limit(5)
                                ->get([
                                    'tipos.id      as idTipo',
                                    'tipos.nombre  as nombreTipo'
                                ]);

        if (sizeof($tipos) > 0){
            return json_encode(
                array(
                    "code" => true, 
                    "result"=>$tipos
                    )
            );
        }else{
            return json_encode(
                array(
                    "code" => false, 
                    )
            );
        }

    }
}
