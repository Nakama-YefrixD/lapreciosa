@extends('layouts.blank')
@section('title')
    Reporte
@endsection
@section('content')


<div class="col-lg-12 grid-margin stretch-card" >
    <div class="card">
        
        <div class="card-body">
            <h6 class="card-title">Generar Reporte</h6>
            <div id="frm_generarReporte">
                <button type="button" id= "btn_generarReporteExcel" 
                    class="btn btn-gradient-success btn-rounded btn-fw"><span id="textBoleta">Excel<span></button>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Buscar</h4>
            @csrf
            <div class="row">
                <div class="col-3">
                    <label>Cliente</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_cliente" id="buscar_cliente">
                </div>
                <div class="col-3">
                    <label>Tipo de comprobante</label>
                    <select class="form-control" name ="buscar_comprobante" id="buscar_comprobante">
                        <option value="">SELECCIONA UN COMPROBANTE</option>
                        <option value="BOLETA">BOLETA</option>
                        <option value="FACTURA">FACTURA</option>
                    </select>
                </div>
                <div class="col-3">
                    <label>Numero de comprobante</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_numeroComprobante" id="buscar_numeroComprobante">
                </div>
                <div class="col-3">
                    <label>Filtro por Fechas</label>
                    <input type="text" class="form-control form-control-lg" name="buscar_fecnumeroComprobante" id="buscar_fecnumeroComprobante" value=''>
                </div>
                    
            </div>
        </div>
    </div> 
</div>



<div class="col-lg-12 grid-margin stretch-card">
    <div class="card">
        <div class="card-body">
            <h6 class="card-title">Comprobantes</h6>
            <table id="tb_ventas" class="table table-striped table-bordered table-sm" cellspacing="0"
                width="100%">
                <thead>
                    <tr>
                        <th>Fecha Emisión</th>
                        <th>Documento Cliente</th>
                        <th>Cliente</th>
                        <th>Tipo Comprobante</th>
                        <th>Serie Comprobante</th>
                        <th>Estado</th>
                        <th>Número</th>
                        <th>Producto</th>
                        <th>Cantidad</th>
                        <th>Precio</th>
                        <th>SubTotal</th>
                        <th>Total</th>
                        <th>SubTotal Venta</th>
                        <th>Total Venta</th>
                    </tr>
                </thead>
            </table>  
        </div>
    </div>
</div>


@endsection

@section('script')
<script type="text/javascript" src="{{ asset('js/reportes/ventas/generarReporte.js') }}"></script>

<script type="text/javascript" src="{{ asset('js/reportes/ventas/tabla.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('input[name="buscar_fecnumeroComprobante"]').daterangepicker({
            autoUpdateInput: false,
            locale: {
                cancelLabel: 'Clear',
                format: 'YYYY/MM/DD',
                "daysOfWeek": [
                    "Do",
                    "Lu",
                    "Ma",
                    "Mi",
                    "Ju",
                    "Vi",
                    "Sa"
                ],
                "monthNames": [
                    "Enero",
                    "Febrero",
                    "Marzo",
                    "Abril",
                    "Mayo",
                    "Junio",
                    "Julio",
                    "Agosto",
                    "Septiembre",
                    "Octubre",
                    "Noviembre",
                    "Diciembre"
                ],
            }
        });
        $('input[name="buscar_fecnumeroComprobante"]').on('apply.daterangepicker', function(ev, picker) {
            $(this).val(picker.startDate.format('YYYY/MM/DD') + ' - ' + picker.endDate.format('YYYY/MM/DD'));
            $("#tb_ventas").DataTable().ajax.reload();
        });

        $('input[name="buscar_fecnumeroComprobante"]').on('cancel.daterangepicker', function(ev, picker) {
            $(this).val('');
            $("#tb_ventas").DataTable().ajax.reload();
        });
    })
</script>


@endsection
