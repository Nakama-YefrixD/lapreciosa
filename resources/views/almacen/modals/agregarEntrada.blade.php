
<div id="productoModal" class="modal fade bd-productoModal-modal-lg" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="card card-default">
                <div class="card-header cabezera">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Producto </h4>
                    <div class="form-group row">
                          <div class="col-sm-3">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="cerrarProducto" value="1" checked="">
                                Cerrar automaticamente
                              <i class="input-helper"></i></label>
                            </div>
                          </div>
                          <div class="col-sm-2">
                            <div class="form-check">
                              <label class="form-check-label">
                                <input type="radio" class="form-check-input" name="membershipRadios" id="abrirProducto" value="0">
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