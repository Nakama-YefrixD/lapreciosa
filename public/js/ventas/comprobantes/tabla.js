$(document).ready(function() {
    var dt = $('#tb_ventas').DataTable({
                "processing": false,
                "serverSide": true,
                "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
                "ajax": "/ventas/tb_ventas", 
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
                    var btnDelete = '<button class="btn btn-sm btn-gradient-primary eliminar" type="button"><i class="mdi mdi-file-pdf"></i></button>';
                    $(nRow).find("td:eq(8)").html(btnDelete);



                    let btnEstado ='';
                    if( aData['estadoSunatVentas'] == 1 ){
                        btnEstado += '<button type="button" class="btn btn-gradient-info btn-rounded btn-icon">';
                        btnEstado += '<i class="mdi mdi-check"></i>';
                    }else{
                        btnEstado += '<button type="button" class="btn btn-gradient-danger btn-rounded btn-icon">';
                        btnEstado += '<i class="mdi mdi-close"></i>';
                    }
                    
                    btnEstado += '</button>';
                    $(nRow).find("td:eq(5)").html(btnEstado);
                    
                }
            });
        
    dt.on( 'order.dt search.dt', function () {
            dt.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = i+1;
        } );
    } ).draw();

















    $("#tb_marcas").on('click', '.editar', function(){
        var data = dt.row($(this).parents('tr')).data();
        let id = data['id'];
        let nombre = data['nombre'];

        $("#editarIdMarca").val(id);
        $("#editarNombreMarca").val(nombre);
        $("#editarMarcaModal" ).modal('show');
    }); 


    $('#editarMarca').on('click', function(e) {
        let data = $('#frm_editarMarca').serialize();
        console.log(data);
        $.confirm({
            icon: 'fa fa-question',
            theme: 'modern',
            animation: 'scale',
            type: 'blue',
            title: '¿Está seguro de editar esta marca?',
            content: 'Recuerda que todos los productos que tengan esta marca asignada cambiaran exponencialmente.',
            buttons: {
                Confirmar: function () {
                    $.ajax({
                        url: '/almacen/marcas/editar',
                        type: 'post',
                        data: data ,
                        dataType: 'json',
                        success: function(response) {
                            if(response['response'] == true) {
                                toastr.success('Se edito la marca satisfactoriamente');
                                $("#tb_marcas").DataTable().ajax.reload();
                                $('#editarMarcaModal').modal('hide');
                            } else {
                                toastr.error('Ocurrio un error al momento de editar esta marca porfavor verifique si todos los campos estan correctos');
                            }
                        },
                        error: function(response) {
                            toastr.error('Ocurrio un error al momento de editar esta marca verifique si todos los campos estan correctos');
                            
                        }
                    });
                },
                Cancelar: function () {
                    
                }
            }
        });
    });


    $("#tb_marcas").on('click', '.eliminar', function(){
        var data = dt.row($(this).parents('tr')).data();
        var id = data['id'];
        var token = $("input[name='_token']").val();
        console.log(id+token);
        $.confirm({
            // icon: 'mdi mdi-delete',
            theme: 'modern',
            // closeIcon: true,
            animation: 'scale',
            type: 'red',
            title:'¿SEGURO DESEA ELIMINAR ESTA MARCA ? ',
            content: 'Los datos eliminados no pueden ser recuperados!',
            buttons: {
                Eliminar: function () {
                    $.ajax({
                            url: "/almacen/marcas/eliminar",
                            type: 'post',
                            data:{id:id , _token: token},
                            dataType: 'json',
                            success:function(response)
                            {
                                if(response['response'] == true) {
                                    $("#tb_marcas").DataTable().ajax.reload();
                                    toastr.success("La marca se elimino correctamente", "Accion Completada");
                                }else{
                                    toastr.error('Debido a que esta marca ya esta asignado a un producto','El tipo de producto no se pudo eliminar');
                                }
                                
                            },
                            error: function(response) {
                                toastr.error('Debido a que esta marca ya esta asignado a un producto','El tipo de producto no se pudo eliminar');
                            }
                        })
                    
                },
                Cancelar: function () {
                    toastr.warning("Accion Cancelada");                        
                },
                
            }
        });
    }); 
})