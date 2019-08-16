$(document).ready(function() {
    var URLactual = window.location.href;
    var ultimateUrl = URLactual.substr(-1,1)
    var x  = URLactual.length
    if(ultimateUrl == '/' || ultimateUrl == '#' ){
        var URLactual = URLactual.substr(0,x-1);
    }
    
    var dt = $('#tb_almacen').DataTable({
                    "processing": false,
                    "serverSide": true,
                    "language": { 'url': "//cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json" },
                    "ajax": URLactual+"/tb_almacen", 
                    "columns":[
                        
                        { "data": "idProducto" },
                        { "data": "nombreMarca" },
                        { "data": "nombreProducto" },
                        { "data": "precioProducto" },
                        { "data": "cantidadProducto" },
                    ],


                    // "fnRowCallback": function(nRow, aData, iDisplayIndex) {
                    //     var btnEdit = '<button data-toggle="modal" data-target="#editarAlumno" ';
                    //     var btnEdit = btnEdit+ 'class="btn btn-xs btn-warning editar">';
                    //     var btnEdit = btnEdit+ '<i class="fa fa-edit"></i></button>';
                    //     var btnDelete = '<button class="btn btn-xs btn-danger eliminar">';
                    //     var btnDelete = btnDelete+'<i class="fa fa-trash"></i></button>';

                    //     $(nRow).find("td:eq(6)").html(btnEdit+" "+btnDelete);
                        
                    // }
                });
})