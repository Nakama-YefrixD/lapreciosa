import React from 'react'
import { Link } from 'react-router-dom'
import {Component} from 'react';
import VentaBoleta from  './ventaBoleta';
import VentaFactura from './ventaFactura';

class Venta extends Component {
    constructor(){
        super();
        this.state ={

            venta_tb :[],
            addComponentFactura: false,
            addComponentBoleta:false,
            dataFacturaReady:false,
            dataBoletaReady:false,
            dataFactura:{},
            dataBoleta:{}
        }
        this.addFactura = this.addFactura.bind(this);
        this.addBoleta = this.addBoleta.bind(this);
        this.fechtDataFactura = this.fechtDataFactura.bind(this);
        this.fechtDataBoleta = this.fechtDataBoleta.bind(this);
        this.fetchVentaDataTabla = this.fetchVentaDataTabla.bind(this);
    }

      
        
        
    fechtDataFactura(){
      fetch('/ventas/factura/serie')
      .then(res => res.json())
      .then(data => {
          this.setState({dataFactura: data},()=>{
            console.log(this.state.dataFactura)
            this.setState({dataFacturaReady: true},()=>console.log(this.state.dataFacturaReady))          
                });                 
        })           
      }
    fechtDataBoleta(){
        fetch('/ventas/boleta/serie')
        .then(res => res.json())
        .then(data => {
            this.setState({dataBoleta: data},()=>{
              console.log(this.state.dataBoleta); 
              this.setState({dataBoletaReady: true},()=>console.log(this.state.dataBoletaReady))         
                  });                 
          })           
        }  
    
    fetchVentaDataTabla(){
          fetch('/ventas/tb_ventas')
              .then(res => res.json())
              .then(data => {
                  this.setState({venta_tb: data},()=>{
                    console.log(this.state.venta_tb)
                  
                  });  
                  
          }) 
        }

    componentDidMount(){
      this.fechtDataFactura();
      this.fechtDataBoleta();
      this.fetchVentaDataTabla();     
    }

    addFactura() {
      this.setState({
        addComponentBoleta: false,
        addComponentFactura : !this.state.addComponentFactura

      })
    }
    addBoleta() {
      this.setState({
        addComponentFactura : false,
        addComponentBoleta : !this.state.addComponentBoleta
      
      })
    }

   /* fetchEntradaDataTabla(){
      fetch('/almacen/entrada/tb_entradas')
          .then(res => res.json())
          .then(data => {
              this.setState({entrada_tb: data},()=>{
                console.log(this.state.entrada_tb)
              
              });  
              
      }) 
}*/

  
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
                          <h6 className="card-title">Generar tipo de venta</h6>
                          <button type="button" id= "btn_factura" onClick={()=>this.addFactura()}
                              className="btn btn-gradient-primary btn-rounded btn-fw desactivado"><span id="textFactura">FACTURA ELECTRÓNICA</span></button>
                          <button type="button" id= "btn_boleta" onClick={()=>this.addBoleta()}
                              className="btn btn-gradient-success btn-rounded btn-fw desactivado"><span id="textBoleta">BOLETA DE VENTA ELECTRÓNICA</span></button>
                      </div>
                  </div> 
               </div>

              { this.state.addComponentFactura && this.state.dataFacturaReady && <VentaFactura  dataFactura={this.state.dataFactura}  />}
              { this.state.addComponentBoleta && this.state.dataBoletaReady && <VentaBoleta   dataBoleta={this.state.dataBoleta} />}


              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Buscar</h4>
                          @csrf
                          <div className="row">
                              <div className="col-3">
                                  <label>Cliente</label>
                                  <input type="text" className="form-control form-control-lg" name="buscar_tb_cliente" id="buscar_tb_cliente"/>
                              </div>
                              <div className="col-3">
                                  <label>Tipo de comprobante</label>
                                  <select className="form-control" name ="buscar_tb_comprobante" id="buscar_tb_comprobante">
                                      <option value="">SELECCIONA UN COMPROBANTE</option>
                                      <option value="BOLETA">BOLETA</option>
                                      <option value="FACTURA">FACTURA</option>
                                  </select>
                              </div>
                              <div className="col-3">
                                  <label>Numero de comprobante</label>
                                  <input type="text" className="form-control form-control-lg" name="buscar_tb_numeroComprobante" id="buscar_tb_numeroComprobante"/>
                              </div>
                              <div className="col-3">
                                  <label>Filtro por Fechas</label>
                                  <input type="text" className="form-control form-control-lg" name="buscar_tb_fecnumeroComprobante" id="buscar_tb_fecnumeroComprobante" defaultValue=''/>
                              </div>
                                  
                          </div>
                      </div>
                  </div> 
              </div>
              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h6 className="card-title">Comprobantes</h6>
                          <table id="tb_ventas" className="table table-striped" style={{width:'100%'}}>
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
                                      {
                                        this.state.venta_tb.data ?
                                        this.state.venta_tb.data.map(task =>{
                                            return (
                                                <tr key={task.idVentas}>
                                                    <th>1</th>
                                                    <th>{task.fechaVentas}</th>
                                                    <th>{task.nombreClientes}</th>
                                                    <th>{task.nombreTiposcomprobante}</th>                                                                                   
                                                    <th>{task.numeroVentas}</th>
                                                    <th>{task.estadoSunatVentas}</th>
                                                    <th>{task.subTotalVentas}</th>
                                                    <th>{task.totalVentas}</th>

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

            </div>
        </div>
      </div>
    </div>
)
}}

export default Venta
