$(document).ready(function() {
    var dt = $('#tb_ventas').DataTable({
        "order": [[ 6, "desc" ]],
        "processing": true,
        'searching': false,
        "serverSide": true,
        "scrollX": true,
        "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
        "ajax": {
            'url':"/reporte/tb_ventas",
            'type' : 'get',
            'data': function(d) {
                d.bcliente = $('#buscar_cliente').val();
                d.bcomprobante = $('#buscar_comprobante').val();
                d.bnumeroComprobante = $('#buscar_numeroComprobante').val();

                if($('#buscar_fecnumeroComprobante').val().length > 2 ){
                    let rangeDates = $('#buscar_fecnumeroComprobante').val();
                    var arrayDates = rangeDates.split(" ");
                    var dateSpecificOne =  arrayDates[0].split("/");
                    var dateSpecificTwo =  arrayDates[2].split("/");

                    d.dateOne = dateSpecificOne[0]+'-'+dateSpecificOne[1]+'-'+dateSpecificOne[2];
                    d.dateTwo = dateSpecificTwo[0]+'-'+dateSpecificTwo[1]+'-'+dateSpecificTwo[2];
                }
                
            }
        
        },

        "columns":[
            { "data": "fechaVentas"             },
            { "data": "documentoClientes"       },
            { "data": "nombreClientes"          },
            { "data": "nombreTiposcomprobante"  },
            { "data": "serieTiposcomprobante"   },
            { "data": "estadoSunatVentas"       },
            { "data": "numeroVentas"            },
            { "data": "nombreProducto"          },
            { "data": "cantidadDetalleVenta"    },
            { "data": "precioProducto"          },
            { "data": "subTotalProductoVenta"   },
            { "data": "totalProductoVenta"      },
            { "data": "subTotalVentas"          },
            { "data": "totalVentas"             },

        ],
        "fnRowCallback": function(nRow, aData, iDisplayIndex) {
            let btnEstado ='';
            
            if( aData['estadoSunatVentas'] == 1 ){
                btnEstado += '<button type="button" class="btn btn-gradient-info btn-rounded btn-icon">';
                btnEstado += '<i class="mdi mdi-check"></i></button>';
            }else if(aData['estadoSunatVentas'] == 0){
                btnEstado += '<button type="button" class="btn btn-gradient-danger btn-rounded btn-icon enviarSunat">';
                btnEstado += '<i class="mdi mdi-close"></i></button>';
            }else if(aData['estadoSunatVentas'] == 2){
                btnEstado += '<label class="badge badge-gradient-danger">CANCELADO</label>';
            }
            
            btnEstado += '</button>';
            $(nRow).find("td:eq(5)").html(btnEstado);
            
            
        }
    });

    $('#buscar_tb_fecnumeroComprobante').change(function() {
        $("#tb_ventas").DataTable().ajax.reload();
   });

   $('#buscar_comprobante').change(function() {
        $("#tb_ventas").DataTable().ajax.reload();
    });
   

   $('#buscar_cliente').on('keyup', function() {
        $("#tb_ventas").DataTable().ajax.reload();
   });

   $('#buscar_numeroComprobante').on('keyup', function() {
        $("#tb_ventas").DataTable().ajax.reload();
    });

})

