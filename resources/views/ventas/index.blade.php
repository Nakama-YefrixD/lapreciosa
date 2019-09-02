@extends('layouts.blank')
@section('title')
    Ventas
@endsection
@section('content')
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Generar tipo de venta</h6>
            <button type="button" id= "btn_factura" 
                class="btn btn-gradient-primary btn-rounded btn-fw desactivado"><span id="textFactura">FACTURA ELECTRÓNICA<span></button>
            <button type="button" id= "btn_boleta" 
                class="btn btn-gradient-success btn-rounded btn-fw desactivado"><span id="textBoleta">BOLETA DE VENTA ELECTRÓNICA<span></button>
        </div>
    </div> 
</div>

<div id="formularioElectronico">

</div>
<!-- 
<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Comprobantes</h6>
            <table id="tb_almacen" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th>T.Gratuito</th>
                        <th>T.Exonerado</th>
                        <th>T.Gravado</th>
                        <th>T.Igv</th>
                        <th>total</th>
                        <th>Descargas</th>
                        <th>Anulación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>  
        </div>
    </div>
</div> -->


<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Comprobantes</h6>
            <table id="tb_ventas" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Fecha Emisión</th>
                        <th>Cliente</th>
                        <th>Tipo Comprobante</th>
                        <th>Número</th>
                        <th>Estado</th>
                        <th>SubTotal</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
            </table>  
        </div>
    </div>
</div>


@endsection

@section('script')

<script type="text/javascript" src="{{ asset('js/ventas/comprobantes/tabla.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('#btn_factura').on('click', function() {
            if ($('#btn_boleta').hasClass('activado')){
                $("#btn_boleta").removeClass(" btn-gradient-danger");
                $("#btn_boleta").removeClass("activado");
                $("#btn_boleta").addClass("btn-gradient-primary");
                $("#btn_boleta").addClass("desactivado");
                $('#textBoleta').html('BOLETA DE VENTA ELECTRÓNICA');
                $('#formularioElectronico').html('');
            }
            if ($(this).hasClass('desactivado')){
                $.confirm({
                    title: 'FACTURA ELECTRÓNICA',
                    theme: 'modern',
                    animation: 'scale',
                    type: 'purple',
                    content: function(){
                        var self = this;
                        self.setContent('Estamos listos para empezar a facturar!');
                        return $.ajax({
                            url: '/ventas/loads/frmfactura',
                            method: 'get'
                        }).done(function (response) {
                            // self.setContentAppend(response);
                            $("#btn_factura").removeClass("btn-gradient-primary");
                            $("#btn_factura").removeClass("desactivado");
                            $("#btn_factura").addClass("btn-gradient-danger");
                            $("#btn_factura").addClass("activado");
                            $('#textFactura').html('CANCELAR FACTURA ELECTRÓNICA');
                            
                            $('#formularioElectronico').html(response);
                        }).fail(function(){
                            // self.setContentAppend('<div>Fail!</div>');
                        }).always(function(){
                            // self.setContentAppend('<div>Always!</div>');
                        });
                    },
                    buttons: {
                        Aceptar:{
                            text: 'Aceptar',
                            btnClass: 'btn-primary',
                            action: function(){
                            }
                        },
                    },
                    
                    onContentReady: function(){
                        // this.setContentAppend('<div>Content ready!</div>');
                    }
                });
            }else{
                $("#btn_factura").removeClass(" btn-gradient-danger");
                $("#btn_factura").removeClass("activado");
                $("#btn_factura").addClass("btn-gradient-primary");
                $("#btn_factura").addClass("desactivado");
                $('#textFactura').html('FACTURA ELECTRÓNICA');
                $('#formularioElectronico').html('');
            }
        });

        $('#btn_boleta').on('click', function() {
            if ($('#btn_factura').hasClass('activado')){
                $("#btn_factura").removeClass(" btn-gradient-danger");
                $("#btn_factura").removeClass("activado");
                $("#btn_factura").addClass("btn-gradient-primary");
                $("#btn_factura").addClass("desactivado");
                $('#textFactura').html('FACTURA ELECTRÓNICA');
                $('#formularioElectronico').html('');
            }
            if ($(this).hasClass('desactivado')){
                $.confirm({
                    title: 'BOLETA ELECTRÓNICA',
                    theme: 'modern',
                    animation: 'scale',
                    type: 'green',
                    content: function(){
                        var self = this;
                        self.setContent('Estamos listos para empezar con la boleta!');
                        return $.ajax({
                            url: '/ventas/loads/frmboleta',
                            method: 'get'
                        }).done(function (response) {
                            // self.setContentAppend(response);
                            $("#btn_boleta").removeClass("btn-gradient-primary");
                            $("#btn_boleta").removeClass("desactivado");
                            $("#btn_boleta").addClass("btn-gradient-danger");
                            $("#btn_boleta").addClass("activado");
                            $('#textBoleta').html('CANCELAR BOLETA DE VENTA ELECTRÓNICA');
                            
                            $('#formularioElectronico').html(response);
                        }).fail(function(){
                            // self.setContentAppend('<div>Fail!</div>');
                        }).always(function(){
                            // self.setContentAppend('<div>Always!</div>');
                        });
                    },
                    buttons: {
                        Aceptar:{
                            text: 'Aceptar',
                            btnClass: 'btn-success',
                            action: function(){
                            }
                        },
                    },
                    
                    onContentReady: function(){
                        // this.setContentAppend('<div>Content ready!</div>');
                    }
                });
            }else{
                $("#btn_boleta").removeClass(" btn-gradient-danger");
                $("#btn_boleta").removeClass("activado");
                $("#btn_boleta").addClass("btn-gradient-primary");
                $("#btn_boleta").addClass("desactivado");
                $('#textBoleta').html('BOLETA DE VENTA ELECTRÓNICA');
                $('#formularioElectronico').html('');
            }
            
            
        });
        
    })

</script>


@endsection
