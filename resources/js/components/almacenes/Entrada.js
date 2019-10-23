import React from 'react'
import { Link } from 'react-router-dom'
import {Component} from 'react';
import EntradaCrear from  './EntradaCrear';

class Entrada extends Component {
    constructor(){
        super();
        this.state ={

            entrada_tb :[],
            addContainer: false
        }
        this.fechaEntradaDataTabla=this.fetchEntradaDataTabla.bind(this);
        this.add = this.add.bind(this)   
    }

    componentDidMount(){
      this.fetchEntradaDataTabla();      
    }

    add() {
      this.setState({addContainer : !this.state.addContainer})
    }

    fetchEntradaDataTabla(){
      fetch('/almacen/entrada/tb_entradas')
          .then(res => res.json())
          .then(data => {
              this.setState({entrada_tb: data},()=>{
                console.log(this.state.entrada_tb)
              
              });  
              
      }) 
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
                          <button type="button" className="btn btn-gradient-primary btn-rounded btn-fw" onClick={() => this.add()}>Agregar Entrada</button>
                      </div>
                  </div> 
              </div>
               { this.state.addContainer && <EntradaCrear/>} 

              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Registro de entradas</h4>
                          <table id="tb_entradas" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                      <th>Numero</th>
                                      <th>RUC proveedor</th>
                                      <th>Proveedor</th>
                                      <th>Fecha</th>
                                      <th>Opciones</th>
                                  </tr>
                                     {
                                      this.state.entrada_tb.data ?
                                      this.state.entrada_tb.data.map(task =>{
                                          return (
                                              <tr key={task.idEntrada}>
                                                  <th>{task.facturaEntrada}</th>
                                                  <th>{task.rucProveedor}</th>
                                                  <th>{task.nombreProveedor}</th>                                                                                   
                                                  <th>{task.fechaEntrada}</th>         
                                                  <th>
                                                  <button className="btn btn-sm btn-gradient-primary ver" type="button" data-toggle="modal" data-target="#entradaDetalladaModal"><i className="mdi mdi-eye"></i></button>
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


              <div id="entradaDetalladaModal" className="modal fade bd-entradaDetalladaModal-lg" role="dialog">
                  <div className="modal-dialog modal-lg">
                      <div className="modal-content">
                          <div className="card card-default">
                              <div className="card-header cabezera">
                                  <button type="button" className="close" data-dismiss="modal">&times;</button>
                                  <h4> Detalles de la entrada </h4>
                              </div>
                              <div className="modal-body">
                                  <div className="card-body" id="entradaDetalladaModalBody">
                                  <label>Proveedor:</label><br/>
                                  <span id="proveedorEntradaDetalle"></span>
                                  <br/><label>Numero entrada:</label><br/>
                                  <span id="numeroEntradaDetalle"></span>
                                  <br/><label>Fecha de emisón:</label><br/>
                                  <span id="fechaEntradaDetalle"></span><br/><br/>
                                  <table className="table table-bordered" id="tablaDetallesEntradaModal">
                                  <thead>
                                    <tr>
                                      <th>
                                        #
                                      </th>
                                      <th>
                                        Producto
                                      </th>
                                      <th>
                                        Precio
                                      </th>
                                      <th>
                                        Cantidad
                                      </th>
                                      <th> Importe </th>
                                    </tr>
                                  </thead>
                                  <tbody>
                                    
                                  </tbody>
                                </table>
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
export default Entrada

