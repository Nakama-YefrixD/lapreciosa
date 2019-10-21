$(document).ready(function() {
    var dt = $('#tb_ventas').DataTable({
        "processing": true,
        'searching': false,
        "serverSide": true,
        "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
        "ajax": {
            'url':"/ventas/tb_ventas",
            'type' : 'get',
            'data': function(d) {
                d.bcliente = $('#buscar_tb_cliente').val();
                d.bcomprobante = $('#buscar_tb_comprobante').val();
                d.bnumeroComprobante = $('#buscar_tb_numeroComprobante').val();

                if($('#buscar_tb_fecnumeroComprobante').val().length > 2 ){
                    let rangeDates = $('#buscar_tb_fecnumeroComprobante').val();
                    var arrayDates = rangeDates.split(" ");
                    var dateSpecificOne =  arrayDates[0].split("/");
                    var dateSpecificTwo =  arrayDates[2].split("/");

                    d.dateOne = dateSpecificOne[0]+'-'+dateSpecificOne[1]+'-'+dateSpecificOne[2];
                    d.dateTwo = dateSpecificTwo[0]+'-'+dateSpecificTwo[1]+'-'+dateSpecificTwo[2];
                }
                
            }
        
        },

        "columns":[
            { "data": "idVentas"                },
            { "data": "fechaVentas"             },
            { "data": "nombreClientes"          },
            { "data": "nombreTiposcomprobante"  },
            { "data": "numeroVentas"            },
            { "data": "estadoSunatVentas"       },
            { "data": "subTotalVentas"          },
            { "data": "totalVentas"             },
            { "data": "idVentas"                },

        ],
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            var btnPdf = '<button class="btn btn-sm btn-gradient-primary" type="button"><i class="mdi mdi-file-pdf pdfVer"></i></button>';
            var btnXml = '<button type="button" class="btn btn-gradient-success btn-rounded btn-icon descargarXml">';
            btnXml     = btnXml + '<i class="mdi mdi-file-xml"></i></button>';

            $(nRow).find("td:eq(8)").html(btnPdf +"   "+btnXml);



            let btnEstado ='';
            if( aData['estadoSunatVentas'] == 1 ){
                btnEstado += '<button type="button" class="btn btn-gradient-info btn-rounded btn-icon">';
                btnEstado += '<i class="mdi mdi-check"></i></button>';
            }else{
                btnEstado += '<button type="button" class="btn btn-gradient-danger btn-rounded btn-icon enviarSunat">';
                btnEstado += '<i class="mdi mdi-close"></i></button>';
            }
            
            btnEstado += '</button>';
            $(nRow).find("td:eq(5)").html(btnEstado);
            
        }
    });

    // DESCARGAR XML
    $("#tb_ventas").on('click', '.descargarXml', function(){
        var data = dt.row($(this).parents('tr')).data();
        let idVenta = data['idVentas'];

        window.open("/ventas/xml/"+idVenta);
        
    });

    // VER VENTA
    $("#tb_ventas").on('click', '.pdfVer', function(){
        var data = dt.row($(this).parents('tr')).data();
        let idVenta = data['idVentas'];

        window.open("/ventas/pdf/"+idVenta, '_blank');
        
    });
    dt.on( 'order.dt search.dt', function () {
            dt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

    $('#buscar_tb_fecnumeroComprobante').change(function() {
        $("#tb_ventas").DataTable().ajax.reload();
   });

   $('#buscar_tb_comprobante').change(function() {
        $("#tb_ventas").DataTable().ajax.reload();
    });
   

   $('#buscar_tb_cliente').on('keyup', function() {
        $("#tb_ventas").DataTable().ajax.reload();
   });

   $('#buscar_tb_numeroComprobante').on('keyup', function() {
        $("#tb_ventas").DataTable().ajax.reload();
    });

    $("#tb_ventas").on('click', '.enviarSunat', function(){
        let token = $('input[name="_token"]').val();
        
        console.log(token);
        
        var data = dt.row($(this).parents('tr')).data();
        let id = data['idVentas'];
        console.log(id)
        $.confirm({
            icon: 'fa fa-question',
            theme: 'modern',
            animation: 'scale',
            type: 'blue',
            title: '¿Está seguro de enviar este comprobante a la sunat?',
            content: '',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        url: '/ventas/comprobante/emitir',
                        type: 'post',
                        data: {
                            _token: token,
                            id: id, 
                            
                        },
                        dataType: 'json',
                        success: function(response) {
                            if(response['response'] == true) {
                                toastr.success('Se edito el usuario satisfactoriamente');
                                $("#tb_ventas").DataTable().ajax.reload();
                            } else {
                                toastr.error('Ocurrio un error al momento de enviar este comprobante');
                            }
                        },
                        error: function(response) {
                            toastr.error('Ocurrio un error al momento de enviar este comprobante');
                        }
                    });
                },
                Cancelar: function () {
                    
                }
            }
        });
    }); 
})

