@extends('layouts.blank')
@section('title')
    Almacen
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Modulos de agregar</h4>
            <button type="button" data-toggle="modal" data-target="#entradaModal" class="btn btn-gradient-primary btn-rounded btn-fw">Agregar Entrada</button>
            <button type="button" data-toggle="modal" data-target="#productoModal" class="btn btn-gradient-success btn-rounded btn-fw">Agregar Producto</button>
            <button type="button" data-toggle="modal" data-target="#marcaModal" class="btn btn-gradient-info btn-rounded btn-fw">Agregar Marca</button>
            <button type="button" data-toggle="modal" data-target="#proveedorModal" class="btn btn-gradient-warning btn-rounded btn-fw">Agregar Proveedor</button>
        </div>
    </div> 
</div>


<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <table id="tb_almacen" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Marca</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
            </table>  
        </div>
    </div>
</div>



<div id="entradaModal" class="modal fade bd-entradaModal-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Entrada </h4>
                    <div class="form-group row">
                          <div class="col-sm-3">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="cerrar" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="abrir" value="0">
                                Mantenerla abierta
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_entrada">
                        <!-- <form role="form" method="post" accept-charset="utf-8" id="frm_entrada" 
                                enctype="multipart/form-data"> -->
                            @csrf
                            <input type='hidden' value='1' name= "cantidadProductos" id="cantidadProductos">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label>Proveedor</label>
                                        <div class="input-group">
                                            <select class="form-control" name="proveedor" id="proveedores" style="width: 90%;">
                                                @foreach($proveedores as $proveedor)
                                                    <option value="{{ $proveedor->id }}" > {{ $proveedor->nombre }} </option>
                                                @endforeach
                                            </select>
                                            <div class="input-group-append">
                                                <button class="btn btn-sm btn-gradient-primary" type="button"><i class="mdi mdi-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <label>Numero de factura</label>
                                        <input type="text" class="form-control" name="factura" id="factura" >
                                    </div>
                                    <div class="col-3">
                                        <label>Fecha</label>
                                        <input type="text" class="form-control" id="date" name="fecha" placeholder="YYYY-MM-DD" autocomplete="off" >
                                    </div>
                                        
                                </div>
                            </div>
                            <div class="form-group" id="listProductos">
                                <div class="row">
                                    <div class="col-5">
                                        <label>Producto</label><br>
                                        <select class="form-control" id="productos" name="producto[]" style="width: 100%; height: 550px;" >
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" >{{ $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-3">
                                        <label>Precio</label>
                                        <input type="text" class="form-control" name="precio[]" id="precio">
                                    </div>
                                    <div class="col-3">
                                        <label>Cantidad</label>
                                        <input type="text" class="form-control" name="cantidad[]" id="cantidad" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="button" id="agregarProducto" class="btn btn-gradient-warning btn-rounded btn-fw">Agregar producto</button>
                            </div>
                            
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearEntrada">
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="productoModal" class="modal fade bd-productoModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Producto </h4>
                    <div class="form-group row">
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="productoEstado" id="cerrarProducto" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="productoEstado" id="abrirProducto" value="0">
                                Mantenerla abierta
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_producto">
                        <!-- <form role="form" method="post" accept-charset="utf-8" id="frm_producto" 
                                enctype="multipart/form-data"> -->
                            @csrf
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label>Marcas</label>
                                        <div class="input-group">
                                            <select class="form-control" name="marca" id="marca" style="width: 100%;">
                                                @foreach($marcas as $marca)
                                                    <option value="{{ $marca->id }}" > {{ $marca->nombre }} </option>
                                                @endforeach
                                            </select>
                                            <!-- <div class="input-group-append">
                                                <button class="btn btn-sm btn-gradient-primary" type="button"><i class="mdi mdi-plus"></i></button>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label>Nombre del producto</label>
                                        <input type="text" class="form-control" name="producto" id="producto" >
                                    </div>
                                </div>
                            </div>
                            <div class="form-group" id="listProductos">
                                <div class="row">
                                    <div class="col-6">
                                        <label>Precio</label>
                                        <input type="text" class="form-control" name="precio" id="precio">
                                    </div>
                                    <div class="col-6">
                                        <label>Cantidad</label>
                                        <input type="text" class="form-control" name="cantidad" id="cantidad" >
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto">
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="marcaModal" class="modal fade bd-marcaModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Marca </h4>
                    <div class="form-group row">
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="productoEstado" id="cerrarProducto" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="productoEstado" id="abrirProducto" value="0">
                                Mantenerla abierta
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_producto">
                        <!-- <form role="form" method="post" accept-charset="utf-8" id="frm_producto" 
                                enctype="multipart/form-data"> -->
                            @csrf
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-12">
                                        <label>Nombre de la marca</label>
                                        <input type="text" class="form-control" name="marca" id="marca" >
                                    </div>
                                </div>
                            </div>

                            
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto">
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="proveedorModal" class="modal fade bd-proveedorModal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar proveedor </h4>
                    <div class="form-group row">
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="proveedorEstado" id="cerrarProveedor" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-6">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="proveedorEstado" id="abrirProveedor" value="0">
                                Mantenerla abierta
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div class="modal-body">
                    <div class="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_producto">
                        <!-- <form role="form" method="post" accept-charset="utf-8" id="frm_producto" 
                                enctype="multipart/form-data"> -->
                            @csrf
                            
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-6">
                                        <label>Nombre del proveedor</label>
                                        <input type="text" class="form-control" name="marca" id="marca" >
                                    </div>
                                    <div class="col-6">
                                        <label>RUC del proveedor</label>
                                        <input type="text" class="form-control" name="marca" id="marca" >
                                    </div>
                                </div>
                            </div>

                            
                            <div class="form-group boton">
                                <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto">
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/almacen/select2.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('click', '#cerrar', function() {
            $('#cerrar').val('1');
            $('#abrir').val('0');
        });

        $('body').on('click', '#abrir', function() {
            $('#cerrar').val('0');
            $('#abrir').val('1');
        });

        $('#agregarProducto').on('click', function() {
                var cantidadProductos = $('#cantidadProductos').val();
                var nuevaCantidadProductos = $('#cantidadProductos').val(parseInt(cantidadProductos)+1);
                var cantidadProductos = $('#cantidadProductos').val();
                
                let data = '<div class="productosAgregados">';
                data += '<br>';
                data += '<div class="row">';
                data += '<div class="col-5">';
                data += '<select class="form-control productos" name="producto[]" style="width: 100%;" >';
                    @foreach($productos as $producto)
                        data += '<option value="{{ $producto->id }}" >{{ $producto->nombre }}</option>';
                    @endforeach
                data += '</select>';
                data += '</div>';
                data += '<div class="col-3">';
                data += '<input type="text" class="form-control" name="precio[]" >';
                data += '</div>';
                data += '<div class="col-3">';
                data += '<input type="number" class="form-control" name="cantidad[]" >';
                data += '</div>';
                data += '<div class="col-1">';
                
                data += '<button type="button" class="btn btn-gradient-danger btn-rounded btn-icon remove">';
                data += '<i class="mdi mdi-close"></i>';
                data += '</button>';
                data += '</div>';
                data += '</div>';
                $('#listProductos').append(data);
                $('.productos').select2();
                
            });
            $('body').on('click', '.remove', function() {
                $(this).parent().parent().parent().remove();
                var cantidadProductos = $('#cantidadProductos').val();
                var nuevaCantidadProductos = $('#cantidadProductos').val(parseInt(cantidadProductos)-1);
            });


            $('#crearEntrada').on('click', function(e) {
                    let data = $('#frm_entrada').serialize();
                    console.log(data);
                    $.confirm({
                        icon: 'fa fa-question',
                        theme: 'modern',
                        animation: 'scale',
                        type: 'blue',
                        title: '¿Está seguro de crear esta entrada?',
                        content: false,
                        buttons: {
                            Confirmar: function () {
                                $.ajax({
                                    url: '/almacen/entrada/crear',
                                    type: 'post',
                                    data: data ,
                                    dataType: 'json',
                                    success: function(response) {
                                        if(response['response'] == true) {
                                            toastr.success('Se grabó satisfactoriamente la entrada');
                                            $("#tb_almacen").DataTable().ajax.reload();
                                            $('.productosAgregados').remove();
                                            $('#cantidadProductos').val('1');
                                            $('#factura').val(' ');
                                            $('#date').val(' ');
                                            $('#precio').val(' ');
                                            $('#cantidad').val(' ');
                                            if($('#cerrar').val() == 1){
                                                $('#entradaModal').modal('hide');
                                            }

                                            
                                        } else {
                                            // toastr.error(response.responseText);
                                            toastr.error('Ocurrio un error al momento de crear esta entrada porfavor fijate si todos los campos estan correctos');
                                        }
                                    },
                                    error: function(response) {
                                        // toastr.error(response.responseText);
                                        toastr.error('sOcurrio un error al momento de crear esta entrada porfavor fijate si todos los campos estan correctos');
                                        
                                    }
                                });
                            },
                            Cancelar: function () {
                                
                            }
                        }
                    });
            });
    })
</script>
    <script type="text/javascript" src="{{ asset('js/almacen/datatables.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/almacen/date.js') }}"></script>
    
@endsection
