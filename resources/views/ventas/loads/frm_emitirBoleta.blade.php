
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">BOLETA ELECTRÓNICA:</h6>
            <form method="post" role="form" data-toggle="validator" id="frm_editar_producto">
                @csrf
                <div class="form-group">
                    <div class="row">
                        <div class="col-3">
                            <label><i class="mdi mdi-barcode"></i>   Serie:</label>
                            <div class="input-group">
                                <select class="form-control" name="tipoProducto" id="tipoProducto" style="width: 100%;">
                                        <option value="1" > asd </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <label> <i class="mdi mdi-file-document-box"></i>   Número:</label>
                            <input type="text" class="form-control" name="nuevoTipoProducto" id="nuevoTipoProducto" >
                        </div>
                        <div class="col-3">
                            <label> <i class="mdi mdi-calendar-text"></i>    Fecha de emisión:</label>
                            <div class="input-group">
                                <select class="form-control" name="marcaProducto" id="marcaProducto" style="width: 100%;">
                                        <option value="1" > asd</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-3">
                            <label> <i class="mdi mdi-cash-multiple"></i>  Moneda:</label>
                            <input type="text" name="precioVentaProducto" id="precioVentaProducto" class="form-control"
                                pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value="" data-type="currency" placeholder="S/1,000,000.00">
                            <!-- <input type="text" class="form-control" name="producto" id="producto" > -->
                        </div>
                    </div>
                </div>
                <h6 class="card-title">CLIENTE:</h6>
                <div class="form-group" >
                    <div class="row">
                        <div class="col-3">
                            <label>  <i class="mdi mdi-account-card-details"></i>   Tipo de Documento: *</label>
                            <input type="text" class="form-control" name="codigoProductoNuevo" id="codigoProductoNuevo">
                        </div>
                        <div class="col-2">
                            <label>N° de Documento: *</label>
                            <input type="text" class="form-control" name="nombreProductoNuevo" id="nombreProductoNuevo">
                        </div>
                        <div class="col-7">
                            <label>Razón Social: *</label>
                            <input type="text" class="form-control" name="nombreProductoNuevo" id="nombreProductoNuevo">
                        </div>
                    </div>
                </div>
                <div class="form-group" >
                    <div class="row">
                        <div class="col-12">
                            <label><i class="mdi mdi-home"></i>    Dirección: *</label>
                            <input type="text" class="form-control" name="codigoProductoNuevo" id="codigoProductoNuevo">
                        </div>
                    </div>
                </div>
                <h6 class="card-title">DETALLE DOCUMENTO:</h6>
                <div class="row">
                    <div class="col-12">
                        <table class="table table-bordered" id="tbl_products">
                            <thead>
                                <tr>
                                    <th >Codigo</th>
                                    <th width="25%;">Producto</th>
                                    <th>Cantidad</th>
                                    <th>Stock</th>
                                    <th>Precio</th>
                                    <th>SubTotal</th>
                                    <th>Total</th>
                                    <th>Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="input-group">
                                            <input type="text" class="form-control c_detail" name="cd_detail[]">
                                        </div>
                                    </td>
                                    <td>
                                            <select class="form-control" name="tipoProducto" id="tipoProducto" style="width: 100%;">
                                                <option value="1" > asd</option>
                                            </select>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control c_quantity" name="cd_quantity[]" value="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control c_stock" name="c_stock[]" value="0" readonly="">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control c_price" name="cd_price[]" value="0">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control c_subtotal" name="cd_subtotal[]" value="0" readonly="" style="width: 100px;">
                                    </td>
                                    <td>
                                        <input type="text" class="form-control c_total" name="cd_total[]" value="0" readonly="" style="width: 100px;">
                                    </td>
                                    <td>
                                        
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="col-12">
                        <br>
                        
                        <button type="button" class="btn btn-primary" id="btnAddProduct">
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
                                <textarea class="form-control" id="exampleTextarea1" rows="6" placeholder="Escribe aquí una observación"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-5">
                            <div class="content-group">
                                <h6>Resumen:</h6>
                                <div class="table-responsive no-border">
                                    <table class="table">
                                        <tbody>
                                            <tr>
                                                <th>Subtotal:</th>
                                                <td class="text-right">
                                                    S/. <span id="subtotal_documento">0</span>
                                                    <input type="hidden" name="txt_subtotal_comprobante" id="txt_subtotal_comprobante" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>IGV: <span class="text-regular">(18%)</span></th>
                                                <td class="text-right">
                                                    S/. <span id="igv_documento">0</span>
                                                    <input type="hidden" name="txt_igv_comprobante" id="txt_igv_comprobante" value="0">
                                                </td>
                                            </tr>
                                            <tr>
                                                <th>Total:</th>
                                                <td class="text-right text-primary"><h5 class="text-semibold">
                                                    S/. <span id="total_documento">0</span></h5>
                                                    <input type="hidden" name="txt_total_comprobante" id="txt_total_comprobante" value="0">
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
                            <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto">
                                EMITIR DOCUMENTO ELECTRÓNICO</button>
                        </div>
                        <div class="col-6">
                            <button type="button" class="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto">
                                GUARDAR DOCUMENTO ELECTRÓNICO</button>
                        </div>
                    </div>
                    
                </div>
            </form>
        </div>
    </div> 
</div>


<script type="text/javascript">
    $('#btnAddProduct').on('click', function() {
        console.log('click');
        let data = '<tr>';
        data += '<td><input type="text" class="form-control c_detail" name="cd_detail[]" /></td>';
        data += '<td><input type="number" class="form-control c_quantity" name="cd_quantity[]" value="0" style="width: 100%;"/></td>';
        data += '<td><input type="text" class="form-control c_availability" name="c_availability[]"></td>';
        data += '<td><input type="number" class="form-control c_price" name="cd_price[]" value="0" style="width: 100%;" /></td>';
        data += '<td><input type="text" class="form-control c_subtotal" name="cd_subtotal[]" value="0" readonly style="width: 100px;" /></td>';
        data += '<td><input type="text" class="form-control c_total" name="cd_total[]" value="0" readonly style="width: 100px;" /></td>';
        data += '<td>';
            data += '<button type="button" class="btn btn-danger remove"><i class="fa fa-close"></i></button>';
        data += '</td>';

        data += '</tr>';
        $('#tbl_products tbody').append(data);
    });
</script>