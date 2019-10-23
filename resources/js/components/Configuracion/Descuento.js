import React from 'react'
import { Link } from 'react-router-dom'

const Descuento = () => (
  
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
                              data-target="#crearDescuentoModal" 
                              className="btn btn-gradient-primary btn-rounded btn-fw">Crear un nuevo descuento</button>
                      </div>
                  </div> 
                </div>

              <div className="col-lg-12 grid-margin stretch-card">
                  <div className="card">
                      <div className="card-body">
                          <h4 className="card-title">Descuentos</h4>
                          <table id="tb_descuentos" className="table table-striped" style={{width:'100%'}}>
                              <thead>
                                  <tr>
                                      <th>Codigo del descuento</th>
                                      <th>Codigo del producto</th>
                                      <th>Nombre del producto</th>
                                      <th>Cantidad</th>
                                      <th>Porcentaje</th>
                                      <th>Opciones</th>
                                  </tr>
                              </thead>
                          </table>  
                      </div>
                  </div>
              </div>

              <div id="crearDescuentoModal" className="modal fade bd-crearDescuentoModal-modal" role="dialog">
                  <div className="modal-dialog">
                      <div className="modal-content">
                          <div className="card card-default">
                              <div className="card-header cabezera">
                                  <button type="button" className="close" data-dismiss="modal">&times;</button>
                                  <h4> Agregar descuento </h4>
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
                                      <form method="post" role="form" data-toggle="validator" id="frm_descuentoCrear">
                                          @csrf
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>BUSCA TU PRODUCTO POR EL <b>CODIGO</b></label>
                                                      <select className="form-control" name="codigoProductoCrear" id="codigoProductoCrear" style={{width: '90%'}} >
                                                          @foreach($productos as $producto)
                                                              <option value="{{ $producto->id }}" > Producto.codigo </option>
                                                          @endforeach
                                                      </select>
                                                  </div>
                                              </div>
                                          </div>
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>BUSCA TU PRODUCTO POR SU <b>NOMBRE</b></label>
                                                      <select className="form-control" name="nombreProductoCrear" id="nombreProductoCrear" style={{width: '90%'}}>
                                                          @foreach($productos as $producto)
                                                              <option value="{{ $producto->id }}" > Producto.Nombre </option>
                                                          @endforeach
                                                      </select>
                                                  </div>
                                              </div>
                                          </div>
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-6">
                                                      <label> Porcentaje % </label>
                                                      <input type="email" className="form-control" name="porcentajeCrear" id="porcentajeCrear"/>
                                                  </div>
                                                  <div className="col-6">
                                                      <label>CANTIDAD</label>
                                                      <input type="number" className="form-control" name="cantidadCrear" id="cantidadCrear"/>  
                                                  </div>
                                              </div>
                                          </div>
                                          
                                          <button type="button" className="btn btn-gradient-primary mr-2" id="descuentoCrear">CREAR</button>
                                          <button type="button" className="btn btn-light" data-dismiss="modal">CANCELAR</button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              <div id="editarDescuentoModal" className="modal fade bd-editarDescuentoModal-modal" role="dialog">
                  <div className="modal-dialog">
                      <div className="modal-content">
                          <div className="card card-default">
                              <div className="card-header cabezera">
                                  <button type="button" className="close" data-dismiss="modal">&times;</button>
                                  <h4> Editar descuento </h4>
                              </div>
                              <div className="modal-body">
                                  <div className="card-body">
                                      <form method="post" role="form" data-toggle="validator" id="frm_descuentoEditar">
                                          @csrf
                                          <input type="hidden" name="idEditar" id="idEditar"/>
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-12">
                                                      <label>BUSCA TU PRODUCTO POR EL <b>CODIGO</b></label>
                                                      <select className="form-control" name="codigoProductoEditar" id="codigoProductoEditar" style={{width: '100%'}} >
                                                          @foreach($productos as $producto)
                                                              <option value="{{ $producto->id }}" > Producto.Codigo - Producto.Nombre </option>
                                                          @endforeach
                                                      </select>
                                                  </div>
                                              </div>
                                          </div>
                                          <div className="form-group">
                                              <div className="row">
                                                  <div className="col-6">
                                                      <label> Porcentaje % </label>
                                                      <input type="email" className="form-control" name="porcentajeEditar" id="porcentajeEditar"/>
                                                  </div>
                                                  <div className="col-6">
                                                      <label>CANTIDAD</label>
                                                      <input type="number" className="form-control" name="cantidadEditar" id="cantidadEditar"/>  
                                                  </div>
                                              </div>
                                          </div>
                                          
                                          <button type="button" className="btn btn-gradient-primary mr-2" id="descuentoEditar">EDITAR</button>
                                          <button type="button" className="btn btn-light" data-dismiss="modal">CANCELAR</button>
                                      </form>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>


              <div id="pruebaDescuentoModal" className="modal fade bd-pruebaDescuentoModal-modal" role="dialog">
                  <div className="modal-dialog">
                      <div className="modal-content">
                          <div className="card card-default">
                              <div className="card-header cabezera">
                                  <button type="button" className="close" data-dismiss="modal">&times;</button>
                                  <h4> Realizar pruebas de descuentos </h4>
                              </div>
                              <div className="modal-body">
                                  <div className="card-body">
                                      <div className="form-group">
                                          <div className="row">
                                              <div className="col-6">
                                                  <label> PRODUCTO: </label><br/>
                                                  <span id="productoDescuentoPrueba"></span>
                                              </div>
                                              <div className="col-6">
                                                  <label>PRECIO: </label><br/>
                                                  <span id="precioProductoPrueba"></span>
                                              </div>
                                          </div>
                                      </div>
                                      
                                      <div className="form-group">
                                          <div className="row">
                                              <div className="col-6">
                                                  <label> CANTIDAD: </label><br/>
                                                  <span id="cantidadDescuentoPrueba"></span>
                                              </div>
                                              <div className="col-6">
                                                  <label>PORCENTAJE %:</label><br/>
                                                  <span id="porcentajeDescuentoPrueba"></span>
                                              </div>
                                          </div>
                                      </div>

                                      <div className="form-group">
                                          <div className="row">
                                              <div className="col-12">
                                                  <label> CANTIDAD: </label>
                                                  <input type="number" className="form-control" name="cantidadPrueba" id="cantidadPrueba"/>  
                                              </div>
                                          </div>
                                      </div>

                                      <div className="form-group">
                                          <div className="row">
                                              <div className="col-6">
                                                  <label> IMPORTE SIN DESCUENTO: </label><br/>
                                                  <span id="importeSDPrueba">0.00</span>
                                              </div>
                                              <div className="col-6">
                                                  <label>IMPORTE CON DESCUENTO:</label><br/>
                                                  <span id="importeCDPrueba">0.00</span>
                                              </div>
                                          </div>
                                      </div>
                                      <div className="form-group">
                                          <div className="row">
                                              <div className="col-6">
                                                  <label> TOTAL DE DESCUENTO: </label><br/>
                                                  <span id="totalDescuentoPrueba">0.00</span>
                                              </div>
                                              <div className="col-6">
                                                  <label>VECES QUE SE APLICA EL DESCUENTO:</label><br/>
                                                  <span id="vecesDescuentoPrueba">0</span>
                                              </div>
                                          </div>
                                      </div>
                                      <button type="button" className="btn btn-gradient-primary mr-2" id="descuentoCrear">CREAR</button>
                                      <button type="button" className="btn btn-light" data-dismiss="modal">CANCELAR</button>
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

export default Descuento
