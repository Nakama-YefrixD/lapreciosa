import React from 'react'
import { Link } from 'react-router-dom'

const Marca = () => (
  
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
                          <button type="button" data-toggle="modal" data-target="#marcaModal" className="btn btn-gradient-info btn-rounded btn-fw">Agregar Marca</button>
                      </div>
                  </div> 
              </div>


              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Marcas</h4>
                          <table id="tb_marcas" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                      <th>Nombre de la marca</th>
                                      <th>Opciones</th>
                                  </tr>
                              </thead>
                          </table>  
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
                                          @csrf
                                          
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>Nombre de la marca</label>
                                                      <input type="text" className="form-control" name="nombreMarca" id="nombreMarca" />
                                                  </div>
                                              </div>
                                          </div>

                                          
                                          <div className="form-group boton">
                                              <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="crearMarca">
                                                  Agregar</button>
                                          </div>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>



              <div id="editarMarcaModal" className="modal fade bd-editarMarcaModal" role="dialog">
                  <div className="modal-dialog ">
                      <div className="modal-content">
                          <div className="card card-default">
                              <div className="card-header cabezera">
                                  <button type="button" className="close" data-dismiss="modal">&times;</button>
                                  <h4>Editar marca </h4>
                              </div>
                              <div className="modal-body">
                                  <div className="card-body">
                                      <form method="post" role="form" data-toggle="validator" id="frm_editarMarca">
                                          @csrf
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>Nombre de la marca</label>
                                                      <input type="text" className="form-control" name="editarNombreMarca" id="editarNombreMarca" />
                                                      <input type="hidden" name="editarIdMarca" id="editarIdMarca" />
                                                  </div>
                                              </div>
                                          </div>
                                          <div className="form-group boton">
                                              <button type="button" className="addexis form-control btn btn-block btn-success btn-lg" id="editarMarca">
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

export default Marca