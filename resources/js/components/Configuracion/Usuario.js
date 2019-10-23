import React from 'react'
import { Link } from 'react-router-dom'

const Usuario = () => (
  
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
                          <h4 className="card-title">Acciones</h4>
                          <button type="button" 
                              data-toggle="modal" 
                              data-target="#usuarioModal" 
                              className="btn btn-gradient-primary btn-rounded btn-fw">Agregar Usuario</button>
                      </div>
                  </div> 
              </div>

              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Usuarios</h4>
                          <table id="tb_usuarios" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                      <th>Codigo</th>
                                      <th>Nombre</th>
                                      <th>Email</th>
                                     
                                      <th>Opciones</th>
                                  </tr>
                              </thead>
                          </table>  
                      </div>
                  </div>
              </div>


              <div id="usuarioModal" className="modal fade bd-usuarioModal-modal" role="dialog">
                  <div className="modal-dialog">
                      <div className="modal-content">
                          <div className="card card-default">
                              <div className="card-header cabezera">
                                  <button type="button" className="close" data-dismiss="modal">&times;</button>
                                  <h4> Agregar Usuario </h4>
                                  <div className="form-group row">
                                        <div className="col-sm-6">
                                          <div className="form-check">
                                            <label className="form-check-label">
                                              <input type="radio" className="form-check-input" name="membershipRadios" id="cerrar" value="1" defaultChecked=""/>
                                              Cerrar automaticamente
                                            <i className="input-helper"></i></label>
                                          </div>
                                        </div>
                                        <div className="col-sm-6">
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
                                      <form method="post" role="form" data-toggle="validator" id="frm_usuario">
                                          @csrf
                                          <div className="form-group">
                                              <label htmlFor="exampleInputUsername1">NOMBRE COMPLETOS</label>
                                              <input type="text" className="form-control" name="nombreUsuarioCrear" id="nombreUsuarioCrear" placeholder="NOMBRE Y APELLIDOS"/>
                                          </div>
                                          <div className="form-group">
                                              <label htmlFor="exampleInputEmail1">DIRECCION EMAIL (CON ESTA INFORMACIÓN INICIAMOS SESSIÓN)</label>
                                              <input type="email" className="form-control" name="emailUsuarioCrear" id="emailUsuarioCrear" placeholder="Email"/>
                                          </div>
                                          <div className="form-group">
                                              <label htmlFor="exampleInputPassword1">CONTRASEÑA</label>
                                              <input type="password" className="form-control" name="contrasenaUsuarioCrear" id="contrasenaUsuarioCrear" placeholder="**********"/>
                                          </div>
                                          <div className="form-group">
                                              <label htmlFor="exampleInputConfirmPassword1">REPITA LA CONTRASEÑA</label>
                                              <input type="password" className="form-control" id="contrasenaRepetirUsuarioCrear" placeholder="**********"/>
                                          </div>
                                          <button type="button" className="btn btn-gradient-primary mr-2" id="crearUsuario">REGISTRAR</button>
                                          <button type="button" className="btn btn-light" data-dismiss="modal">CANCELAR</button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>



              <div id="editarUsuarioModal" className="modal fade bd-editarUsuarioModal-modal" role="dialog">
                  <div className="modal-dialog">
                      <div className="modal-content">
                          <div className="card card-default">
                              <div className="card-header cabezera">
                                  <button type="button" className="close" data-dismiss="modal">&times;</button>
                                  <h4> Editar Usuario </h4>
                              </div>
                              <div className="modal-body">
                                  <div className="card-body">
                                      <form method="post" role="form" data-toggle="validator" id="frm_usuarioEditar" autoComplete="off">
                                          @csrf
                                          <div className="form-group">
                                              <label htmlFor="exampleInputUsername1">NOMBRE COMPLETOS</label>
                                              <input type="text" className="form-control" name= "nombreUsuarioEditar" id="nombreUsuarioEditar" placeholder="NOMBRE Y APELLIDOS"/>
                                              <input type="hidden" name= "datosUsuarioEditar" id="datosUsuarioEditar" value="0" />
                                              <input type="hidden" name= "idUsuarioEditar" id="idUsuarioEditar"/>
                                          </div>
                                          <div className="form-group">
                                              <label htmlFor="exampleInputEmail1">DIRECCION EMAIL (CON ESTA INFORMACIÓN INICIAMOS SESSIÓN)</label>
                                              <input type="email" className="form-control" name="emailUsuarioEditar" id="emailUsuarioEditar" placeholder="Email"/>
                                          </div>
                                          <div className="form-group">
                                              <label htmlFor="exampleInputPassword1">NUEVA CONTRASEÑA</label>
                                              <input type="password" className="form-control" name="contrasenaUsuarioEditar" id="contrasenaUsuarioEditar" placeholder="**********" autoComplete="off"/>
                                          </div>
                                          <div className="form-group">
                                              <label htmlFor="exampleInputConfirmPassword1">REPITA LA NUEVA CONTRASEÑA</label>
                                              <input type="password" className="form-control" id="" placeholder="**********"/>
                                          </div>
                                          <button type="button" id="editarUsuarios"               className="btn btn-gradient-primary mr-2">EDITAR</button>
                                          <button type="button" id="editarUsuariosCredenciales"   className="btn btn-gradient-warning mr-1">EDITAR CONTRASEÑA</button>
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

export default Usuario
