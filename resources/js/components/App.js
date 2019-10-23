
    import React, { Component } from 'react'
    import ReactDOM from 'react-dom'
    import { BrowserRouter, Route, Switch } from 'react-router-dom'
    import Top from './top'
    import Almacen from './almacenes/Almacen'
    import Entrada from './almacenes/Entrada'
    import Marca from './almacenes/Marca'
    import Proveedores from './almacenes/Proveedores'
    import Tipoproducto from './almacenes/Tipoproducto'
    import Queso from './almacenes/queso'
    import Descuentos from './Configuracion/Descuento'
    import Usuarios from './Configuracion/Usuario'
    import Ventas from './ventas/Venta'


    class App extends Component {
      render () {
        return (
          <BrowserRouter>
            <div>
              <Top/>
              <Switch>
                <Route exac path='/queso' component={Queso} />

                <Route
                  path="/almacen"
                  render={({ match: { url } }) => (
                    <>
                      <Route path={`${url}/`} component={Almacen} exact />
                      <Route path={`${url}/entrada`} component={Entrada} />
                      <Route path={`${url}/marcas`} component={Marca} />
                      <Route path={`${url}/proveedor`} component={Proveedores} />
                      <Route path={`${url}/tiposproductos`} component={Tipoproducto} />
                    </>
                  )}
                />
                <Route
                  path="/configuracion"
                  render={({ match: { url } }) => (
                    <>
                      <Route path={`${url}/descuentos`} component={Descuentos} />
                      <Route path={`${url}/usuarios`} component={Usuarios} />
                    </>
                  )}
                />

                <Route exac path='/ventas' component={Ventas} />
              </Switch>

            </div>
          </BrowserRouter>
        )
      }
    }

    ReactDOM.render(<App />, document.getElementById('app'))