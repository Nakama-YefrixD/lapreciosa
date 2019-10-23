import React from 'react';
import { Link } from 'react-router-dom';
import {Component} from 'react';
import DatePicker from "react-datepicker";
import "react-datepicker/dist/react-datepicker.css";
import { registerLocale, setDefaultLocale } from  "react-datepicker";
import format from 'date-fns/format';
import es from 'date-fns/locale/es';
registerLocale('es', es);




class Almacen extends Component{
    
    constructor(){
        super();
        this.state ={
            tasks: [],
            //data editar productos
            idProducto:'',
            codigoP:'',
            nombreM: '',
            nombreT: '',
            nombreP: '',
            precioP: '',
            idMarca:'',
            idTipo:'',

            cantidadP: '',
            tasksM:[],
            tasksTP:[],
            tiposproductos:[],
            marcas:[],

            idProveedor:'', 
            idFactura:'',
            datepick:'',
            nowday:'',
            precio:'',
            cantidad:'',  
            proveedores:[],
            nuevoTipoProducto:'',

            precioVentaProducto:'',
            codigoProductoNuevo:'',
            nombreProductoNuevo:'',

            rucProveedor:'',
            RUCdatos:[],
            telefonoProveedor:'',
            nombreProveedor:'',
            direccionProveedor:'',
            tipoProveedor:''

        };

        this.fetchTasks = this.fetchTasks.bind(this);
        this.editTask = this.editTask.bind(this);
        this.handleChange = this.handleChange.bind(this);
        this.handleChangeTime = this.handleChangeTime.bind(this);
        this.handleChangeRUC = this.handleChangeRUC.bind(this);
        this.suprimirDatos = this.suprimirDatos.bind(this);
    }
    
    cleanInputs(){

        this.setState({
            idProducto:'',
            codigoP:'',
            nombreM: '',
            nombreT: '',
            nombreP: '',
            precioP: '',
            idMarca:'',
            idTipo:'',
            cantidadP: '',
            idProveedor:'', 
            idFactura:'',
            datepick:'',
            nowday:'',
            precio:'',
            cantidad:'',  
            nuevoTipoProducto:'',
            precioVentaProducto:'',
            codigoProductoNuevo:'',
            nombreProductoNuevo:'',
            rucProveedor:'',
            telefonoProveedor:'',
            nombreProveedor:'',
            direccionProveedor:'',
            tipoProveedor:''
        })   

    }

    fechAgregarTipo(){
        fetch('/almacen/tiposproductos/tb_tiposProductos')
                .then(res => res.json())
                .then(data => {
                    this.setState({tiposproductos: data}, () => {
                        this.suprimirDatos();
                    });                    
        })
    }

    fetchRUC(){
        fetch(`/consult/ruc/${this.state.rucProveedor}`,
        {
            method: 'POST',
            body: JSON.stringify({
                '_token': csrf_token,
            }),
            headers: {
                'Accept' : 'application/json',
                'Content-Type': 'application/json',              
            }
        }
    )

        .then(res => res.json())
        .then(data => {
            this.setState({RUCdatos: data}, () => {
                this.datosdeRUC();
            });                    
    })
}   

    datosdeRUC(){

        this.state.RUCdatos ? 
                    this.setState({
                        telefonoProveedor:this.state.RUCdatos.telefonos[0],
                        nombreProveedor:this.state.RUCdatos.razonSocial,
                        direccionProveedor:this.state.RUCdatos.direccion,
                        tipoProveedor:this.state.RUCdatos.tipo
                },console.log(this.state.RUCdatos)) :null
    }

    fetchTasks(){
            fetch('/almacen/tb_almacen')
                .then(res => res.json())
                .then(data => {
                    this.setState({tasks: data});  
                    console.log(this.state.tasks);
            }) 

            fetch('/almacen/proveedores/tb_proveedores')
                        .then(res => res.json())
                        .then(data => {
                            this.setState({proveedores: data});  
                                    
                })  
            
            fetch('/almacen/tiposproductos/tb_tiposProductos')
                        .then(res => res.json())
                        .then(data => {
                            this.setState({tiposproductos: data});  
                })

            fetch('/almacen/marcas/tb_marcas')
                .then(res => res.json())
                .then(data => {
                this.setState({marcas: data});  
            })
    }

    suprimirDatos(){
            let x = [];
            let y = [] ;
            let b = [];
            let q= [];
            let c = ()=>{
                this.state.marcas.data ?
                this.state.marcas.data.map(task=>{             
                   x.push({
                       nombreMarca: task.nombre,
                       idMarca: task.id
                    });
                }) :null
                b = [ ...new Map(x.map(obj => [`${obj.nombreMarca}:${obj.idMarca}`, obj]))
                .values()]; 
                console.log(b); 
                return b;     
            } 
            var d = ()=>{
                this.state.tiposproductos.data ?
                this.state.tiposproductos.data.map(task=>{             
                   y.push({
                      nombreTipo: task.nombre,
                      idTipo: task.id
                    });
                }) :null
                q = [ ...new Map(y.map(obj => [`${obj.nombreTipo}:${obj.idTipo}`, obj]))
                .values()];  
                return q; 
            }
    
    
            this.setState({
                tasksM: c(),
                tasksTP: d()
            })
            
            
    }
    editTask(idP,codeP,nameM, nameT,nameP,precioP,idM,idT){
        console.log(codeP,nameM, nameT,nameP,precioP,idM,idT);
        let x = [];
        let y = [] ;
        let b = [];
        let q= [];
        let c = ()=>{
            this.state.tasks.data ?
            this.state.tasks.data.map(task=>{             
               x.push({
                   nombreMarca: task.nombreMarca,
                   idMarca: task.idMarca
                });
            }) :null
            b = [ ...new Map(x.map(obj => [`${obj.nombreMarca}:${obj.idMarca}`, obj]))
            .values()]; 
            console.log(b); 
            return b;     
        } 
        var d = ()=>{
            this.state.tasks.data ?
            this.state.tasks.data.map(task=>{             
               y.push({
                  nombreTipo: task.nombreTipo,
                  idTipo: task.idTipo
                });
            }) :null
            q = [ ...new Map(y.map(obj => [`${obj.nombreTipo}:${obj.idTipo}`, obj]))
            .values()];  
            return q; 
        }


        this.setState({
            idProducto: idP,
            codigoP: codeP,
            nombreM: nameM,
            nombreT: nameT,
            nombreP: nameP,
            precioP: precioP,
            idMarca: idM,
            idTipo:idT,
            tasksM: c(),
            tasksTP: d()
        })
        
        }

    sendAgregarProveedor(){
        fetch('/almacen/proveedor/crear',
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    rucProveedor:this.state.rucProveedor,
                    telefonoProveedor:this.state.telefonoProveedor,
                    nombreProveedor:this.state.nombreProveedor,
                    direccionProveedor:this.state.direccionProveedor,
                    tipoProveedor:this.state.tipoProveedor
                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-Type': 'application/json',          
                
                }
            }
        )
        .then(res =>res.json())
        .then(data => {    
            console.log("correcto actualizado Proveedor:");
            console.log(data);
            
        });  
    }    

    sendAgregarTipoProducto(){

            fetch(`/almacen/tipo/crear`,
            {
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    nuevoTipoProducto: this.state.nuevoTipoProducto
                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-Type': 'application/json',          
                
                }
            }
        )
        .then(res =>res.json())
        .then(data => {    
            console.log("correcto actualizado entrada:");
            console.log(data);
            this.fechAgregarTipo()
            
            
        });  }
    sendAgregarProducto(){

        fetch(`/almacen/producto/crear`,
        {
            method: 'POST',
            body: JSON.stringify({
                '_token': csrf_token,
                precioVentaProducto:this.state.precioVentaProducto,
                codigoProductoNuevo:this.state.codigoProductoNuevo,
                marcaProducto:this.state.idMarca,
                tipoProducto:  this.state.idTipo,
                nombreProductoNuevo: this.state.nombreProductoNuevo
            }),
            headers: {
                'Accept' : 'application/json',
                'Content-Type': 'application/json',          
            
            }
        }
    )
    .then(res =>res.json())
    .then(data => {    
        console.log("correcto actualizado entrada:");
        console.log(data);
        this.fetchTasks();
        this.cleanInputs();
        
        
    });  }

    sendAgregarEntrada(){

        console.log(this.state.idProveedor,
            this.state.idFactura,
            this.state.datepick,
            this.state.precio,
             this.state.cantidad,
            this.state.idProducto)
        fetch(`/almacen/entrada/crear`,
        {
            method: 'POST',
            body: JSON.stringify({
                '_token': csrf_token,
                proveedor: this.state.idProveedor,
                factura: this.state.idFactura,
                fecha: this.state.datepick,
                precio: this.state.precio,
                cantidad: this.state.cantidad,
                producto: this.state.idProducto,
                cantidadProducto: 1

                
            }),
            headers: {
                'Accept' : 'application/json',
                'Content-Type': 'application/json',          
            
            }
        }
    )
    .then(res =>res.json())
    .then(data => {    
        console.log("correcto actualizado entrada:");
        console.log(data);
        this.fetchTasks();
    });
    }   

    sendTask(){

        fetch(`/almacen/producto/editar`,
        {
            method: 'POST',
            body: JSON.stringify({
                '_token': csrf_token,
                editarIdProducto: this.state.idProducto,
                editarCodigoProductoNuevo: this.state.codigoP,
                editarMarcaProducto: this.state.idMarca,
                editarTipoProducto: this.state.idTipo,
                editarNombreProductoNuevo: this.state.nombreP,
                editarPrecioVentaProducto: this.state.precioP

                
            }),
            headers: {
                'Accept' : 'application/json',
                'Content-Type': 'application/json',          
            
            }
        }
    )
    .then(res => res.json())
    .then(data => {    
        console.log("correcto actualizado:");
        console.log(data);
        this.fetchTasks();
    });
        }   
    deleteTask(id){
        console.log(id);
            fetch(`/producto/eliminar`,{
                method: 'POST',
                body: JSON.stringify({
                    '_token': csrf_token,
                    id: id
                }),
                headers: {
                    'Accept' : 'application/json',
                    'Content-type' : 'application/json'
                }
            })
            .then(res => res.json())
            .then(data =>{
                console.log(data);
                this.fetchTasks();
            })
        }

     handleChange (e){
            const {name , value} = e.target;
            this.setState({
                [name] : value
                
            })
            console.log(name);
            console.log(value);
            console.log(this.state.idProveedor);

        }   
    handleChangeRUC(e){
        const {name , value} = e.target;

        if(value.length == 11){
            this.setState({
                [name] : value
                
            }, () => {
                this.fetchRUC();
            })
            console.log(value);
        }

    }   

     handleChangeTime (date){
         this.setState({
             datepick: format(date, 'yyyy-MM-dd'),
             nowday: date
         });
         console.log(this.state.datepick);
     };
    componentDidMount(){
        this.fetchTasks();
        console.log(csrf_token);       
    }

    

    render(){
        return(
    <div className="container-fluid page-body-wrapper" >
      <nav className="sidebar sidebar-offcanvas" id="sidebar">
        <ul className="nav">
          <li className="nav-item nav-profile">
            <Link className="nav-link" to='/queso'>
              <div className="nav-profile-image">
                <img src="/home" alt="profile"/>
                <span className="login-status online"></span>             
              </div>
              <div className="nav-profile-text d-flex flex-column">
                <span className="font-weight-bold mb-2">user</span>
                <span className="text-secondary text-small">Vendedor</span>
              </div>
              <i className="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </Link>
          </li>

          <li className="nav-item">
            <a className="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
              <span className="menu-title">Almacén</span>
              <i className="menu-arrow"></i>
              <i className="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div className="collapse" id="ui-basic">
              <ul className="nav flex-column sub-menu">
                <li className="nav-item"> <Link className="nav-link" to="/almacen">Almacen</Link></li>
                <li className="nav-item"> <Link className="nav-link" to="/almacen/entrada">Entradas</Link></li>
                <li className="nav-item"> <Link className="nav-link" to="/almacen/proveedor">Proveedores</Link></li>
                <li className="nav-item"> <Link className="nav-link" to="/almacen/tiposproductos">Tipos producto</Link></li>
                <li className="nav-item"> <Link className="nav-link" to="/almacen/marcas">Marcas</Link></li>  
              </ul>
            </div>
          </li>
          <li className="nav-item">
           <Link className="nav-link" to="/ventas">
              <span className="menu-title">Ventas</span>
              <i className="mdi mdi-shopping menu-icon"></i>
            </Link>
          </li>
          <li className="nav-item">
            <a className="nav-link" data-toggle="collapse" href="#ui-configuracion" aria-expanded="false" aria-controls="ui-basic">
              <span className="menu-title">Configuración</span>
              <i className="menu-arrow"></i>
              <i className="mdi mdi-crosshairs-gps menu-icon"></i>
            </a>
            <div className="collapse" id="ui-configuracion">
              <ul className="nav flex-column sub-menu"> 
                <li className="nav-item"> <Link className="nav-link" to="/configuracion/descuentos">Descuentos</Link></li>
                <li className="nav-item"> <Link className="nav-link" to="/configuracion/usuarios">Usuarios</Link></li>
              </ul>
            </div>
          </li>
          <li className="nav-item">
            <a className="nav-link" href="#">
              <span className="menu-title">Estadisticas</span>
              <i className="mdi mdi-shopping menu-icon"></i>
            </a>
          </li>
          <li className="nav-item">
            <a className="nav-link" href="#">
              <span className="menu-title">Perfil</span>
              <i className="mdi mdi-shopping menu-icon"></i>
            </a>
          </li>
        </ul>
      </nav>
        <div className="main-panel" style={{float:'left'}}>
        <div className="content-wrapper">
            <div className="row">

            <div className="col-lg-12 grid-margin stretch-card">
              <div className="card">
                <div className="card-body">


                  <h4 className="card-title">Modulos de agregar</h4>
                  <button type="button" data-toggle="modal" data-target="#entradaModal" className="btn btn-gradient-primary btn-rounded btn-fw">Agregar Entrada</button>
                  <button type="button" data-toggle="modal" data-target="#productoModal" className="btn btn-gradient-success btn-rounded btn-fw" onClick={()=>this.suprimirDatos()}>Agregar Producto</button>
                  <button type="button" data-toggle="modal" data-target="#marcaModal" className="btn btn-gradient-info btn-rounded btn-fw">Agregar Marca</button>
                  <button type="button" data-toggle="modal" data-target="#proveedorModal" className="btn btn-gradient-warning btn-rounded btn-fw">Agregar Proveedor</button>
                 </div>
              </div> 
          </div>

        <div className="col-lg-12 grid-margin stretch-card">
            <div className="card">
                <div className="card-body">
            <h4 className="card-title">Buscar</h4>
            <div className="row">
                <div className="col-3">
                    <label>Buscar Codigo</label>
                    <input type="text" className="form-control form-control-lg" name="buscar_tb_codigo" id="buscar_tb_codigo"/>
                </div>
                <div className="col-3">
                    <label>Buscar Marca</label>
                    <input type="text" className="form-control form-control-lg" name="buscar_tb_marca" id="buscar_tb_marca"/>
                </div>
                <div className="col-3">
                    <label>Buscar Tipo</label>
                    <input type="text" className="form-control form-control-lg" name="buscar_tb_tipo" id="buscar_tb_tipo"/>
                </div>
                <div className="col-3">
                    <label>Buscar Nombre</label>
                    <input type="text" className="form-control form-control-lg" name="buscar_tb_nombre" id="buscar_tb_nombre"/>
                </div>
                    
                </div>
            </div>
        </div> 
    </div>

<div className="col-lg-12 grid-margin stretch-card">
    <div className="card">
        <div className="card-body">
            <h4 className="card-title">Almacen de productos</h4>
            <table id="tb_almacen" className="table table-striped" style={{width: '100%'}}>
                <thead>
                    <tr>
                        <th>Codigo</th>
                        <th>Marca</th>
                        <th>Tipo</th>
                        <th>Nombre</th>
                        <th>Precio</th>
                        <th>Cantidad</th>
                        <th>Opciones</th>
                    </tr>
                    {
                        this.state.tasks.data ?
                        this.state.tasks.data.map(task =>{
                            return (
                                <tr key={task.codigoProducto}>
                                    <th>{task.codigoProducto}</th>
                                    <th>{task.nombreMarca}</th>                                
                                    <th>{task.nombreTipo}</th> 
                                    <th>{task.nombreProducto}</th>         
                                    <th>{task.precioProducto}</th>
                                    <th>{task.cantidadProducto}</th>
                                    <th>
                                     <button onClick={()=> this.deleteTask(task.idProducto)} className="btn btn-sm btn-gradient-danger eliminar" > 
                                         <i className="mdi mdi-delete"> </i>
                                     </button>
                                     <button  data-toggle="modal" onClick={()=> this.editTask(task.idProducto,task.codigoProducto,task.nombreMarca,
                                        task.nombreTipo,task.nombreProducto,task.precioProducto,task.idMarca,task.idTipo)} className="btn btn-sm btn-gradient-secondary editar" data-target="#productoEditarModal">
                                         <i className="mdi mdi-lead-pencil">  </i>
                                     </button>
                                    </th>
                                </tr>
                            );
                        } )   : null
                    }
                </thead>
            </table>  
        </div>
    </div>
</div>



<div id="entradaModal" className="modal fade bd-entradaModal-modal-lg" role="dialog">
    <div className="modal-dialog modal-lg">
        <div className="modal-content">
            <div className="card card-default">
                <div className="card-header cabezera">
                    <button type="button" className="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Entrada </h4>
                    <div className="form-group row">
                          <div className="col-sm-3">
                            <div className="form-check">
                              <label className="form-check-label">
                                <input type="radio" className="form-check-input" name="membershipRadios" id="cerrar" value="1" defaultChecked=""/>
                                Cerrar automaticamente
                              <i className="input-helper"></i></label>
                            </div>
                          </div>
                          <div className="col-sm-2">
                            <div className="form-check">
                              <label className="form-check-label">
                                <input type="radio" className="form-check-input" name="membershipRadios" id="abrir" value="0"/>
                                Mantenerla abierta
                              <i className="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div className="modal-body">
                    <div className="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_entrada">
                           
                            <input type='hidden' value='1' name= "cantidadProductos" id="cantidadProductos"/>
                            <input type='hidden' value='0' name= "agregandoProveedor" id="agregandoProveedor"/>
                            <input type='hidden' value='0' name= "agregandoProducto" id="agregandoProducto"/>

                            <div className="form-group">
                                <div className="row">
                                    <div className="col-6">
                                        <label>Selecciona al proveedor</label>
                                        <div className="input-group">
                                            <select className="form-control" name="idProveedor" id="proveedores" style={{width: '90%'}} onChange={this.handleChange} value={this.state.idProveedor}>

                                                {
                                                    this.state.proveedores.data?
                                                    this.state.proveedores.data.map((data)=>{
                                                        
                                                       return(
                                                        <option value={data.id} > {data.nombre} </option>
                                                       ) 
                                                    }
                                                    )
                                                    :null
                                                }
                
                                            </select>
                                            <div className="input-group-append">
                                                <button className="btn btn-sm btn-gradient-primary" id="agregarNuevoProveedor" type="button"><i className="mdi mdi-plus"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                    <div className="col-3">
                                        <label>Numero de factura</label>
                                        <input type="text" className="form-control" name="idFactura" id="factura" onChange={this.handleChange}/>
                                    </div>
                                    <div className="col-3">
                                        <label>Fecha</label>
                                        <DatePicker locale="es" selected={this.state.nowday} onChange={this.handleChangeTime} />
                                        {/*<input type="text" className="form-control" id="date" name="fecha" placeholder="YYYY-MM-DD" autoComplete="off" />*/}
                                    </div>
                                        
                                </div>
                            </div>
                            <div className="form-group" id="listProductos">
                                <div className="row">
                                    <div className="col-4">
                                        <label>Producto de entrada</label><br/>
                                        <select className="form-control listProductos" id="idProducto" name="idProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idProducto}>
                                            
                                                {
                                                    this.state.tasks.data?
                                                    this.state.tasks.data.map((data)=>{
                                                        
                                                       return(
                                                        <option value={data.idProducto} > {data.nombreProducto} </option>
                                                       ) 
                                                    }
                                                    )
                                                    :null
                                                }
                                            
                                        </select>
                                    </div>
                                    <div className="col-2 precioCompraContainer">
                                        <label>Precio de compra</label>
                                        <input type="text" name="precio" id="precio" className="form-control precioCompra"
                                            pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" defaultValue="" data-type="currency" placeholder="S/1,000,000.00" onChange={this.handleChange}/>
                                    </div>
                                    <div className="col-2 cantidadCompraContainer">
                                        <label>Cantidad</label>
                                        <input type="number" className="form-control cantidadProductoEntrada" name="cantidad" id="cantidad" onChange={this.handleChange}/>
                                    </div>
                                    <div className="col-2 importeCompraContainer">
                                        <label>Importe</label>
                                        <input type="text" name="importe[]" id="importe" className="form-control " pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" defaultValue="" data-type="currency" placeholder="S/1,000,000.00"/>
                                    </div>
                                    <div className="col-1">
                                        <br/>
                                        <button type="button" className="btn btn-gradient-primary btn-rounded btn-icon " id="agregarNuevoProducto" >
                                            <i className="mdi mdi-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <button type="button" id="agregarProducto" className="btn btn-gradient-warning btn-rounded btn-fw">Agregar producto</button>
                            </div>
                            
                            <div className="form-group boton">
                                <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearEntrada" onClick={()=>this.sendAgregarEntrada()}>
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="productoModal" className="modal fade bd-productoModal" role="dialog">
    <div className="modal-dialog ">
        <div className="modal-content">
            <div className="card card-default">
                <div className="card-header cabezera">
                    <button type="button" className="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Producto </h4>
                    <div className="form-group row">
                          <div className="col-sm-6">
                            <div className="form-check">
                              <label className="form-check-label">
                                <input type="radio" className="form-check-input" name="productoEstado" id="cerrarProducto" value="1" defaultChecked=""/>
                                Cerrar automaticamente
                              <i className="input-helper"></i></label>
                            </div>
                          </div>
                          <div className="col-sm-6">
                            <div className="form-check">
                              <label className="form-check-label">
                                <input type="radio" className="form-check-input" name="productoEstado" id="abrirProducto" value="0"/>
                                Mantenerla abierta
                              <i className="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div className="modal-body">
                    <div className="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_producto">
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-12">
                                        <label>Nuevo tipo de producto</label>
                                        <div className="input-group">
                                            <input type="text"  placeholder="Si no existe el tipo de producto agregalo" className="form-control" name="nuevoTipoProducto" id="nuevoTipoProducto" onChange={this.handleChange}  value={this.state.nuevoTipoProducto}/>
                                            <div className="input-group-append">
                                                <button  id="crearTipoProducto" className="btn form-control btn-sm btn-gradient-primary" type="button" onClick={()=>this.sendAgregarTipoProducto()}><i className="mdi mdi-plus"></i></button>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div className="form-group" >
                                <div className="row">
                                    <div className="col-12">
                                        <label>Codigo</label>
                                        <input type="text" className="form-control" name="codigoProductoNuevo" id="codigoProductoNuevo" value={this.state.codigoProductoNuevo} onChange={this.handleChange}/>
                                    </div>
                                    <div className="col-12" id="alertaCodigo">
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-6">
                                        <label>Marcas</label>
                                        <div className="input-group">
                                            <select className="form-control" name="idMarca" id="marcaProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idMarca}>
                                                { 
                                                    this.state.tasksM ?                                                   
                                                    this.state.tasksM.map(element => {                                         
                                                        return (
                                                            <option value={element.idMarca}  >{element.nombreMarca}</option>
                                                            );  
                                                    })
                                                    :null
                                                } 
                                            </select> 
                                        </div>
                                    </div>
                                    <div className="col-6">
                                        <label>Tipos de prodcuto</label>
                                        <div className="input-group">
                                            <select className="form-control" name="idTipo" id="tipoProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idTipo}>
                                                {                                                
                                                    this.state.tasksTP ? 
                                                    this.state.tasksTP.map(element=>{
                                                    return (  
                                                    <option value={element.idTipo}>{element.nombreTipo}</option>                 
                                                    );    
                                                })
                                                 : null                                                
                                                }
                                            </select>    
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-12">
                                        <label>Nombre del producto</label>
                                        <input type="text" className="form-control" name="nombreProductoNuevo" id="nombreProductoNuevo" onChange={this.handleChange} value={this.state.nombreProductoNuevo}/>
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    
                                  { /* <div className="col-6">
                                        <label>Precio de venta</label>
                                        <input type="text" name="precioVentaProductoSinIGV" id="precioVentaProductoSinIGV" className="form-control"
                                            pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" defaultValue="" data-type="currency" placeholder="S/1,000,000.00"/>      
                                            </div> */}

                                    <div className="col-6">
                                        <label>Precio con IGV(18%)</label>
                                        <input type="text" name="precioVentaProducto" id="precioVentaProducto" className="form-control"
                                            pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" data-type="currency" placeholder="S/1,000,000.00" onChange={this.handleChange} value={this.state.precioVentaProducto}/>            
                                    </div>
                                </div>
                            </div>
                            
                            
                            <div className="form-group boton">
                                <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearProducto" onClick={()=>this.sendAgregarProducto()}>
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="marcaModal" className="modal fade bd-marcaModal" role="dialog">
    <div className="modal-dialog ">
        <div className="modal-content">
            <div className="card card-default">
                <div className="card-header cabezera">
                    <button type="button" className="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar Marca </h4>
                    <div className="form-group row">
                          <div className="col-sm-6">
                            <div className="form-check">
                              <label className="form-check-label">
                                <input type="radio" className="form-check-input" name="marcaEstado" id="cerrarMarca" value="1" defaultChecked=""/>
                                Cerrar automaticamente
                              <i className="input-helper"></i></label>
                            </div>
                          </div>
                          <div className="col-sm-6">
                            <div className="form-check">
                              <label className="form-check-label">
                                <input type="radio" className="form-check-input" name="marcaEstado" id="abrirMarca" value="0"/>
                                Mantenerla abierta
                              <i className="input-helper"></i></label>
                            </div>
                          </div>
                        </div>
                </div>
                <div className="modal-body">
                    <div className="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_marca">                            
                            
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-12">
                                        <label>Nombre de la marca</label>
                                        <input type="text" className="form-control" name="nombreMarca" id="nombreMarca" onChange={this.handleChange}/>
                                    </div>
                                </div>
                            </div>

                            
                            <div className="form-group boton">
                                <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearMarca" onClick={()=>this.sendAgregarMarca()}>
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<div id="proveedorModal" className="modal fade bd-proveedorModal" role="dialog">
    <div className="modal-dialog ">
        <div className="modal-content">
            <div className="card card-default">
                <div className="card-header cabezera">
                    <button type="button" className="close" data-dismiss="modal">&times;</button>
                    <h4> Agregar proveedor </h4>
                    <div className="form-group row">
                        <div className="col-sm-6">
                        <div className="form-check">
                            <label className="form-check-label">
                            <input type="radio" className="form-check-input" name="proveedorEstado" id="cerrarProveedor" value="1" defaultChecked=""/>
                            Cerrar automaticamente
                            <i className="input-helper"></i></label>
                        </div>
                        </div>
                        <div className="col-sm-6">
                        <div className="form-check">
                            <label className="form-check-label">
                            <input type="radio" className="form-check-input" name="proveedorEstado" id="abrirProveedor" value="0"/>
                            Mantenerla abierta
                            <i className="input-helper"></i></label>
                        </div>
                        </div>
                    </div>
                </div>
                <div className="modal-body">
                    <div className="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_proveedor">
                            
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-6">
                                        <label>RUC del proveedor</label>
                                        <input type="number" className="form-control" name="rucProveedor" id="rucProveedor" onChange={this.handleChangeRUC}/>
                                    </div>
                                    <div className="col-6">
                                        <label>Telefono</label>
                                        <input type="number" className="form-control" name="telefonoProveedor" id="telefonoProveedor" value={this.state.telefonoProveedor} onChange={this.handleChange}/>
                                    </div>
                                    
                                </div><br/>
                                <div className="row">
                                    <div className="col-12">
                                        <label>Nombre del proveedor</label>
                                        <input type="text" className="form-control" name="nombreProveedor" id="nombreProveedor" value={this.state.nombreProveedor} onChange={this.handleChange}/>
                                    </div>
                                    
                                </div><br/>
                                <div className="row">
                                    <div className="col-12">
                                        <label>Dirección</label>
                                        <input type="text" className="form-control" name="direccionProveedor" id="direccionProveedor" value={this.state.direccionProveedor} onChange={this.handleChange}/>
                                    </div>
                                </div><br/>
                                <div className="row">
                                    <div className="col-12">
                                        <label>Tipo</label>
                                        <input type="text" className="form-control" name="tipoProveedor" id="tipoProveedor" value={this.state.tipoProveedor} onChange={this.handleChange}/>
                                    </div>
                                </div>
                            </div>
                            <div className="form-group boton">
                                <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearProveedor" onClick={()=>this.sendAgregarProveedor()}>
                                    Agregar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div id="productoEditarModal" className="modal fade bd-productoEditarModal" role="dialog">
    <div className="modal-dialog ">
        <div className="modal-content">
            <div className="card card-default">
                <div className="card-header cabezera">
                    <button type="button" className="close" data-dismiss="modal">&times;</button>
                    <h4> Editar Producto </h4>
                </div>
                <div className="modal-body">
                    <div className="card-body">
                        <form method="post" role="form" data-toggle="validator" id="frm_editarProducto">
                            @csrf
                            <div className="form-group" >
                                <div className="row">
                                    <div className="col-12">
                                        <label>Codigo</label>
                                        <input type="hidden" name="editarIdProducto" id="editarIdProducto" value={this.state.idProducto}/>
                                        <input type="text" className="form-control" name="codigoP" id="editarCodigoProductoNuevo" value={this.state.codigoP} onChange={this.handleChange}/>
                                    </div>
                                    <div className="col-12" id="editarAlertaCodigo">
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-6">
                                        <label>Marcas</label>
                                        <div className="input-group">
                                            <select className="form-control" name="idMarca" id="editarMarcaProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idMarca}>
                                                { 
                                                    this.state.tasksM ?
                                                    
                                                    this.state.tasksM.map(element => {                                         
                                                        return (
                                                            <option value={element.idMarca}  >{element.nombreMarca}</option>
                                                            );  
                                                    })
                                                    :null
                                                } 
                                            </select>
                                        </div>
                                    </div>
                                    <div className="col-6">
                                        <label>Tipos de prodcuto</label>
                                        <div className="input-group">
                                            <select className="form-control" name="idTipo" id="editarTipoProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idTipo} >         
                                            
                                           {                                                
                                                    this.state.tasksTP ? 
                                                    this.state.tasksTP.map(element=>{
                                                    return (  
                                                    <option value={element.idTipo}>{element.nombreTipo}</option>                 
                                                    );    
                                                })
                                                 : null

                                                
                                                }

                                                
    
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-12">
                                        <label>Nombre del producto</label>
                                        <input type="text" className="form-control" name="nombreP" id="editarNombreProductoNuevo" value={this.state.nombreP} onChange={this.handleChange} />
                                    </div>
                                </div>
                            </div>
                            <div className="form-group">
                                <div className="row">
                                    <div className="col-12">
                                        <label>Precio con IGV(18%)</label>
                                        <input type="text" name="precioP" id="editarPrecioVentaProducto" className="form-control"
                                            pattern="^\S/\d{1,3}(,\d{3})*(\.\d+)?$" value={this.state.precioP} data-type="currency" placeholder="S/1,000,000.00" onChange={this.handleChange} />                                      
                                    </div>
                                </div>
                            </div>
                            <div className="form-group boton">
                                <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="editarProducto" onClick={()=>{
                                    this.sendTask()
                                }}>
                                    Editar</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


            </div>
        </div>
      </div>
    </div>
    )
}}
export default Almacen
