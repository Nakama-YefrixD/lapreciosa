<?php 
  use\App\Http\Controllers\ProductosController; 
  use\App\Http\Controllers\TiposMonedaController;
  use\App\Http\Controllers\tiposcomprobanteController;
  use\App\Http\Controllers\descuentosProductoController;
  $productos = ProductosController::buscadorProductos();
  $tiposMoneda = TiposMonedaController::index();
  $tiposcomprobante = tiposcomprobanteController::boleta();
  $descuentosProducto = descuentosProductoController::index();
  $fechaActual = date('Y-m-d');
//   $otros = ProductosController::buscadorProductos();
  
  
?>

            <h6 class="card-title">BOLETA ELECTRÓNICA:</h6>
            <form method="post" role="form" data-toggle="validator" id="frm_emitirBoleta">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label><i class="mdi mdi-barcode"></i>   Serie:</label>
                            <input type="hidden" name="tipoComprobante" id="tipoComprobante" value="{{ $tiposcomprobante->id }}" class="form-control" readonly="readonly" >
                            <input type="text" name="serieVenta" id="serieVenta" value="{{ $tiposcomprobante->serie }}" class="form-control" readonly="readonly">
                        </div>
                        <div class="col-3">
                            <label> <i class="mdi mdi-file-document-box"></i>    Nº Boleta:</label>
                            <input type="number" class="form-control" name="facturaVenta" id="facturaVenta" value="{{ $tiposcomprobante->correlativo }}">
                        </div>
                        <div class="col-3">
                            <label> <i class="mdi mdi-calendar-text"></i>    Fecha de emisión:</label>
                            <input type="text" class="form-control" value ="{{ $fechaActual }}" name="dateFactura" id="dateFactura">
                        </div>
                        <div class="col-3">
                            <label> <i class="mdi mdi-cash-multiple"></i>  Moneda:</label>
                            <div class="input-group">
                                <select class="form-control" name="tipoMoneda" id="tipoMoneda" style="width: 100%;">
                                    @foreach($tiposMoneda as $tipoMoneda)
                                        <option value="{{ $tipoMoneda->id }}" > {{ $tipoMoneda->nombre }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <h6 class="card-title">CLIENTE:</h6>
                <div class="form-group" >
                    <div class="row">
                        <div class="col-3">
                            <label>  <i class="mdi mdi-account-card-details"></i>   Tipo de Documento: *</label>
                            <div class="input-group">
                                <select class="form-control" name="tipoDocumento" id="tipoDocumento" style="width: 100%;">
                                    <option value="1" > DNI </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <label>N° de Documento: *</label>
                            <input type="number" class="form-control" name="numeroDocumento" id="numeroDocumento">
                        </div>
                        <div class="col-6">
                            <label>Nombre del cliente: *</label>
                            <input type="text" class="form-control" name="nombreCliente" id="nombreCliente">
                        </div>
                    </div>
                </div>
                <h6 class="card-title">DETALLE DOCUMENTO:</h6>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered" id="tbl_products">
                            <thead>
                                <tr>
                                    <!-- <th width="5%;" >Codigo</th> -->
                                    <th width="25%;">Producto</th>
                                    <th>Cantidad</th>
                                    <th width="2%;">Disponible</th>
                                    <th width="25%;">Precio</th>
                                    <th>Descuento</th>
                                    <th>SubTotal</th>
                                    <th>Total</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <!-- <td class="codigoProducto">
                                        <select class="form-control productos " name="codigoProducto[]" id="codigoProducto" style="width: 100%;">
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" 
                                                    precio="{{ $producto->precio }}" disponible="{{ $producto->cantidad }}" > 
                                                    {{ $producto->id }}</option>
                                            @endforeach
                                        </select>
                                    </td> -->
                                    <td>
                                        <select class="form-control productos " name="nombreProducto[]" id="nombreProducto" style="width: 100%;">
                                            @foreach($productos as $producto)
                                                <option value="{{ $producto->id }}" 
                                                precio="{{ $producto->precio }}"  disponible="{{ $producto->cantidad }}"> {{ $producto->nombre }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="cantidad">
                                        <input type="text" class="form-control c_quantity" name="cantidad[]"  value="0">
                                    </td>
                                    <td class="disponible">
                                        <span>0</span>
                                        <input type="hidden" class="form-control disponible" name="disponible[]" value="0" readonly="">
                                    </td>
                                    <td class="precio">
                                        <span>0</span>
                                        <input type="hidden" class="form-control precio" name="precio[]" value="0" readonly="" style="width: 100%;">
                                    </td>
                                    <td>
                                        <span>0</span>
                                        <input type="hidden" class="form-control descuento" name="descuento[]" value="0" readonly="">
                                    </td>
                                    <td class="subtotal">
                                        <span>0</span>
                                        <input type="hidden" class="form-control subtotal" name="subtotal[]" value="0" readonly="" style="width: 100px;">
                                    </td>
                                    <td class="total">
                                        <span>0</span>
                                        <input type="hidden" class="form-control total" name="total[]" value="0" readonly="" style="width: 100px;">
                                    </td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-12">
                        <br>
                        
                        <button type="button" class="btn btn-warning" id="btnAddProduct">
                            <i class="mdi mdi-plus-circle"></i>   AGREGAR PRODUCTO AL DETALLE
                        </button>
                        <br>
                    </div>
                </div><br>
                <div class="col-md-12" style="margin-top: 15px;">
                    <div class="row">
                        <div class="col-sm-7">
                            <div class="content-group">
                                <h6>Observación:</h6>
                                <textarea class="form-control" name="observacionVenta" id="exampleTextarea1" rows="8" placeholder="Escribe aquí una observación">SN</textarea>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="content-group">
                                <h6>Resumen:</h6>
                                <div class="table-responsive no-border">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th>Descuento:</th>
                                                <td class="text-right">
                                                    S/. <span id="descuentoVentaTexto">0</span>
                                                    <input type="hidden" name="descuentoVenta" id="descuentoVenta" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Subtotal:</th>
                                                <td class="text-right">
                                                    S/. <span id="subTotalVentaTexto">0</span>
                                                    <input type="hidden" name="subTotalVenta" id="subTotalVenta" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>IGV: <span class="text-regular">(18%)</span></th>
                                                <td class="text-right">
                                                    S/. <span id="igvVentaTexto">0</span>
                                                    <input type="hidden" name="igvVenta" id="igvVenta" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td class="text-right text-primary"><h5 class="text-semibold">
                                                    S/. <span id="totalVentaTexto">0</span></h5>
                                                    <input type="hidden" name="totalVenta" id="totalVenta" value="2">
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group boton">
                    <div class="row">
                        <div class="col-6">
                            <button type="button" class=" addexis form-control btn btn-block btn-primary btn-lg" id="emitirBoleta">
                                EMITIR BOLETA ELECTRÓNICA</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class=" addexis form-control btn btn-block btn-success btn-lg" id="guardarBoleta">
                                GUARDAR BOLETA ELECTRÓNICA</button>
                        </div>
                    </div>
                    
                </div>
            </form>
        


<script type="text/javascript">
    // $('.productos').select2();
    $('#codigoProducto').select2();
    $('#nombreProducto').select2();
    

    $(document).ready(function() {
        let productos = '';
        let primerProductoDisponible = 0;
        let primerProductoPrecio = 0;
        let contador = 0;
        @foreach($productos as $producto)
            
            if(contador == 0){
                primerProductoDisponible    =   {{ $producto->cantidad }};
                primerProductoPrecio        =   {{ $producto->precio }};
            }
            productos += '<option precio="{{ $producto->precio }}" disponible="{{ $producto->cantidad }}" value="{{ $producto->id }}">{{ $producto->nombre }}</option>';
            contador = contador +1;
        @endforeach    
        let codigosProductos = '';
        @foreach($productos as $producto)
        codigosProductos += '<option precio="{{ $producto->precio }}" disponible="{{ $producto->cantidad }}" value="{{ $producto->id }}" >{{ $producto->id }}</option>';
        @endforeach

        
            
        
        $('#btnAddProduct').on('click', function() {
            console.log('click');
            let data = '<tr>';
            // data += '<td><select class="form-control productos" name="codigoProducto[]" id="codigoProducto[] " style="width: 100%;">';
            // data += codigosProductos;
            // data += '</select></td>';
            data += '<td><select class="form-control productos" name="nombreProducto[]" id="nombreProducto[] " style="width: 100%;">';
            data += productos;
            data += '</select></td>';
            data += '<td class="cantidad"><input type="text" class="form-control c_quantity" name="cantidad[]" value="0">';
            data += '</td>';
            data += '<td class="disponible"><span>'+primerProductoDisponible+'</span><input type="hidden" class="form-control disponible" name="disponible[]" value="'+primerProductoDisponible+'" readonly="">';
            data += '</td>';
            data += '<td class="precio"><span>'+primerProductoPrecio.toFixed(2)+'</span><input type="hidden" class="form-control precio" name="precio[]" value="'+primerProductoPrecio.toFixed(2)+'" readonly="">';
            data += '</td>';
            data += '<td><span>0</span><input type="hidden" class="form-control descuento" name="descuento[]" value="0" readonly="">';
            data += '</td>';
            data += '<td class="subtotal"><span>0</span><input type="hidden" class="form-control subtotal" name="subtotal[]" value="0" readonly="" style="width: 100px;">';
            data += '</td>';
            data += '<td class="total"><span>0</span><input type="hidden" class="form-control total" name="total[]" value="0" readonly="" style="width: 100px;">';
            data += '</td>';

            data += '<td>';
                data += '<button type="button" class="btn btn-gradient-danger btn-rounded btn-icon remove"><i class="mdi mdi-close"></i></button>';
            data += '</td>';

            data += '</tr>';
            $('#tbl_products tbody').append(data);
            $('.productos').select2();
            calcularTotalVenta();


        });

        $('body').on('click', '.remove', function() {
            $(this).parent().parent().remove();
            calcularTotalVenta();
        });

        $('body').on('click', '.productos', function() {
            $(this).select2();
        });

        $('body').on('change','.productos', function() {
            // console.log('cambio');
            precio = $('option:selected', this).attr('precio');
            disponible = $('option:selected', this).attr('disponible');
            cantidad = $(this).parent().siblings('.cantidad').find('input').val();
            let total = precio * cantidad;
            let igvProducto = (total * 18)/100;
            let subTotal = total - igvProducto;

            $(this).parent().siblings('.disponible').find('input').val(disponible);
            $(this).parent().siblings('.disponible').find('span').html(disponible);

            $(this).parent().siblings('.precio').find('input').val(precio);
            $(this).parent().siblings('.precio').find('span').html(precio);

            $(this).parent().siblings('.total').find('input').val(total.toFixed(2));
            $(this).parent().siblings('.total').find('span').html(total.toFixed(2));

            $(this).parent().siblings('.subtotal').find('input').val(subTotal.toFixed(2));
            $(this).parent().siblings('.subtotal').find('span').html(subTotal.toFixed(2));
            
            calcularTotalVenta();

            

            // console.log('cambio');
        });

        $('body').on('keyup', '.c_quantity', function() {
            cantidad = $(this).val()
            precio = $(this).parent().siblings('.precio').find('input').val();
            let total = precio * cantidad;
            $(this).parent().siblings('.total').find('input').val(total.toFixed(2));
            $(this).parent().siblings('.total').find('span').html(total.toFixed(2));
            let igvProducto = (total * 18)/100;
            let subTotal = total - igvProducto;
            $(this).parent().siblings('.subtotal').find('input').val(subTotal.toFixed(2));
            $(this).parent().siblings('.subtotal').find('span').html(subTotal.toFixed(2));

            calcularTotalVenta();
        });


        $('#emitirBoleta').on('click', function(e) {
            let data = $('#frm_emitirBoleta').serialize();
            console.log(data);
            
            $.confirm({
                icon: 'fa fa-question',
                theme: 'modern',
                animation: 'scale',
                type: 'blue',
                title: '¿Está seguro de emitir esta boleta electrónica?',
                content: 'Si no desea emitirlo todavia lo puedo guardar con el boton de alado.',
                buttons: {
                    Confirmar: function () {
                        $.ajax({
                            url: '/venta/emitirBoleta',
                            type: 'post',
                            data: data,
                            dataType: 'json',
                            success: function(response) {
                                if(response['response'] == true) {
                                    toastr.success('Se emitio satisfactoriamente la boleta electrónica');
                                    $("#btn_boleta").removeClass(" btn-gradient-danger");
                                    $("#btn_boleta").removeClass("activado");
                                    $("#btn_boleta").addClass("btn-gradient-primary");
                                    $("#btn_boleta").addClass("desactivado");
                                    $('#textBoleta').html('BOLETA DE VENTA ELECTRÓNICA');
                                    $('#formularioElectronico').html('');
                                    
                                } else {
                                    // toastr.error(response.responseText);
                                    toastr.error('Ocurrio un error al momento de emitir este tipo de documento electrónico porfavor verifique si todos los campos estan correctos');
                                }
                            },
                            error: function(response) {
                                // toastr.error(response.responseText);
                                toastr.error('Ocurrio un error al momento de emitir este tipo de documento electrónico porfavor verifique si todos los campos estan correctos');
                                
                            }
                        });
                    },
                    Cancelar: function () {
                        
                    }
                }
            });
        });


        $('#guardarBoleta').on('click', function(e) {
            let data = $('#frm_emitirBoleta').serialize();
            console.log(data);
            
            $.confirm({
                icon: 'fa fa-question',
                theme: 'modern',
                animation: 'scale',
                type: 'green',
                title: '¿Está seguro de guardar este documento electrónico?',
                content: 'Si no desea guardarlo lo puede emitir con el boton de alado.',
                buttons: {
                    Confirmar: function () {
                        $.ajax({
                            url: '/venta/guardaremitirBoleta',
                            type: 'post',
                            data: data,
                            dataType: 'json',
                            success: function(response) {
                                if(response['response'] == true) {
                                    toastr.success('Se guardo satisfactoriamente el documento electrónico');
                                    $("#btn_boleta").removeClass(" btn-gradient-danger");
                                    $("#btn_boleta").removeClass("activado");
                                    $("#btn_boleta").addClass("btn-gradient-primary");
                                    $("#btn_boleta").addClass("desactivado");
                                    $('#textBoleta').html('BOLETA DE VENTA ELECTRÓNICA');
                                    $('#formularioElectronico').html('');

                                    
                                } else if(response['response'] == false){
                                    toastr.error('Este numero de factura ya existe.');
                                }else {
                                    // toastr.error(response.responseText);
                                    toastr.error('Ocurrio un error al momento de guardar este tipo de documento electrónico porfavor verifique si todos los campos estan correctos');
                                }
                            },
                            error: function(response) {
                                // toastr.error(response.responseText);
                                toastr.error('Ocurrio un error al momento de guardar este tipo de documento electrónico porfavor verifique si todos los campos estan correctos');
                                
                            }
                        });
                    },
                    Cancelar: function () {
                        
                    }
                }
            });
        });
        
    })
    
    

    $('#numeroDocumento').on('keyup', function() {
        let url = '';
        if($(this).val().length == 8) {
            url = '/consult/dni/' + $(this).val();
            datosCliente(url);
        }
        
    });

    function datosCliente(url)
    {
        $.ajax({
            url: url,
            type: 'post',
            data: {
                '_token': "{{ csrf_token() }}"
            },
            dataType: 'json',
            success: function(response) {
                    // $('#telefonoProveedor').val(response['telefonos']);
                    $('#nombreCliente').val(response['nombres']+" "+response['apellidoPaterno']+" "+response['apellidoMaterno']);
                    // $('#tipoProveedor').val(response['tipo']);
            },
            error: function(response) {
                toastr.info('El ruc no existe.');
            }
        });
    }

    function calcularTotalVenta()
    {
        // VARIABLES DE RESULTADO DE LA VENTA
        let descuentoVenta  = 0;
        let subTotalVenta   = 0;
        let igvVenta        = 0;
        let totalVenta      = 0;

        $('#tbl_products tbody tr').each(function(index, tr) {
            subTotalVenta = (subTotalVenta * 1) + ($(tr).find('.subtotal input').val() * 1);
            totalVenta = (totalVenta * 1) + ($(tr).find('.total input').val() * 1);
            
        });
        
        $('#subTotalVentaTexto').html(subTotalVenta.toFixed(2));
        $('#igvVentaTexto').html((totalVenta - subTotalVenta).toFixed(2));
        $('#totalVentaTexto').html( totalVenta.toFixed(2));

        $('#igvVenta').val((totalVenta - subTotalVenta).toFixed(2));
        $('#subTotalVenta').val(subTotalVenta.toFixed(2));
        $('#totalVenta').val(totalVenta.toFixed(2));
    }

    $(document).ready(function() {
        $('#dateFactura').datepicker({
            'format': 'yyyy-mm-dd',
            'autoclose': true
        });
    })
</script>