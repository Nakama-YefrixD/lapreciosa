import React from 'react'
import { Link } from 'react-router-dom'

const Proveedores = () => (
  
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
                          <button type="button" data-toggle="modal" data-target="#proveedorModal" className="btn btn-gradient-warning btn-rounded btn-fw">Agregar Proveedor</button>
                      </div>
                  </div> 
              </div>


              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Registro de proveedores</h4>
                          <table id="tb_proveedores" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                      <th>RUC proveedor</th>
                                      <th>Proveedor</th>
                                      <th>Telefóno</th>
                                      <th>Dirección</th>
                                      <th>Tipo</th>
                                      <th>Opciones</th>
                                  </tr>
                              </thead>
                          </table>  
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
                                          @csrf
                                          
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-6">
                                                      <label>RUC del proveedor</label>
                                                      <input type="number" className="form-control" name="rucProveedor" id="rucProveedor" />
                                                  </div>
                                                  <div className="col-6">
                                                      <label>Telefono</label>
                                                      <input type="number" className="form-control" name="telefonoProveedor" id="telefonoProveedor" />
                                                  </div>
                                                  
                                              </div><br/>
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>Nombre del proveedor</label>
                                                      <input type="text" className="form-control" name="nombreProveedor" id="nombreProveedor" />
                                                  </div>
                                                  
                                              </div><br/>
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>Dirección</label>
                                                      <input type="text" className="form-control" name="direccionProveedor" id="direccionProveedor" />
                                                  </div>
                                              </div><br/>
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>Tipo</label>
                                                      <input type="text" className="form-control" name="tipoProveedor" id="tipoProveedor" />
                                                  </div>
                                              </div>
                                          </div>
                                          <div className="form-group boton">
                                              <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearProveedor">
                                                  Agregar</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>





              <div id="editarProveedorModal" className="modal fade bd-editarProveedorModal" role="dialog">
                  <div className="modal-dialog ">
                      <div className="modal-content">
                          <div className="card card-default">
                              <div className="card-header cabezera">
                                  <button type="button" className="close" data-dismiss="modal">&times;</button>
                                  <h4> Editar Proveedor </h4>
                              </div>
                              <div className="modal-body">
                                  <div className="card-body">
                                      <form method="post" role="form" data-toggle="validator" id="frm_editarProveedor">
                                          @csrf
                                          <div className="form-group" >
                                              <div className="row">
                                                  <div className="col-6">
                                                      <label>RUC:</label>
                                                      <input type="hidden" name="editarIdProveedor" id="editarIdProveedor"/>
                                                      <input type="text" className="form-control" name="editarRucProveedor" id="editarRucProveedor"/>
                                                  </div>
                                                  <div className="col-6">
                                                      <label>Telefóno:</label>
                                                      <input type="text" className="form-control" name="editarTelefonoProveedor" id="editarTelefonoProveedor"/>
                                                  </div>
                                              </div>
                                          </div>
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>Nombre del proveedor:</label>
                                                      <textarea className="form-control" name="editarNombreProveedor" id="editarNombreProveedor"></textarea>
                                                  </div>
                                              </div>
                                          </div>
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>Dirección:</label>
                                                      <textarea className="form-control" name="editarDireccionProveedor" id="editarDireccionProveedor"></textarea>
                                                  </div>
                                              </div>
                                          </div>
                                          <div className="form-group boton">
                                              <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="editarProveedor">
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

export default Proveedores