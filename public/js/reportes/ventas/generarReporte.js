$('#btn_generarReporteExcel').on('click', function(e) {
    let token   = $('input:hidden[name=_token]').val();
    let cliente = $('#buscar_cliente').val();
    let tipoComprobante = $('#buscar_comprobante').val();
    let numeroComprobante = $('#buscar_numeroComprobante').val();
    let primeraFecha = '';
    let segundaFecha = '';
    console.log(tipoComprobante.length)
    if(cliente.length == 0){
        cliente = null;
    }if(tipoComprobante.length == 0){
        tipoComprobante = null;
    }if(numeroComprobante.length == 0){
        numeroComprobante = null;
    }if(primeraFecha.length == 0){
        primeraFecha = null;
    }if(segundaFecha.length == 0){
        segundaFecha = null;
    }

    if($('#buscar_fecnumeroComprobante').val().length > 2 ){
        let rangeDates = $('#buscar_fecnumeroComprobante').val();
        var arrayDates = rangeDates.split(" ");
        var dateSpecificOne =  arrayDates[0].split("/");
        var dateSpecificTwo =  arrayDates[2].split("/");

        primeraFecha = dateSpecificOne[0]+'-'+dateSpecificOne[1]+'-'+dateSpecificOne[2];
        segundaFecha = dateSpecificTwo[0]+'-'+dateSpecificTwo[1]+'-'+dateSpecificTwo[2];
    }

    var datosEnviar = {
        "cliente"           : cliente,
        "tipoComprobante"   : tipoComprobante,
        "numeroComprobante" : numeroComprobante,
        "primeraFecha"      : primeraFecha,
        "segundaFecha"      : segundaFecha,
        "_token"            : token
    };


    $.confirm({
        icon: 'fa fa-question',
        theme: 'modern',
        animation: 'scale',
        type: 'blue',
        title: '¿Esta seguro de generar este reporte?',
        content: 'El reporte generado tiene las especificaciones de la tabla de abajo!.',
        buttons: {
            Confirmar: function () {
                console.log("/reporte/generar/ventas/"+cliente+"/"+tipoComprobante+"/"+numeroComprobante+"/"+primeraFecha+"/"+segundaFecha);
                window.open("/reporte/generar/ventas/"+cliente+"/"+tipoComprobante+"/"+numeroComprobante+"/"+primeraFecha+"/"+segundaFecha);
                // $.ajax({
                //     url: '/reporte/generar/ventas',
                //     type: 'post',
                //     data: datosEnviar,
                //     dataType: 'json',
                //     success: function(response) {
                //         if(response['response'] == true) {
                            
                //         } else {

                //         }
                //     },
                //     error: function(response) {
                        
                //     }
                // });
            },
            Cancelar: function () {
                
            }
        }
    });
});
