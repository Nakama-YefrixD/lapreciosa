import React from 'react'
import { Link } from 'react-router-dom'
import {Component} from 'react';
import ListProductComponent from './componentsExtras/listProductComponent'

class EntradaCrear extends Component {
    constructor(){
        super();
        this.state ={

            contador:[],
            contadorNumero:0

        }
        this.quitarOpcion = this.quitarOpcion.bind(this);
    }


    generateComponents(){

         this.setState({contadorNumero: this.state.contadorNumero+1},
            ()=>{ this.setState({
             contador: [...this.state.contador, this.state.contadorNumero]},
             ()=>{console.log(this.state.contadorNumero) , console.log(this.state.contador)}
             )
            })
       

    }

    quitarOpcion(numero){
        console.log(numero);

        var array = [...this.state.contador];
        var index = array.indexOf(numero);
        console.log(index);

        if (index > -1) {
        array.splice(index,1);
        console.log(array);    
        this.setState({
            contador: array
        })
    }
    }


    render(){
        return(
            <div className="col-lg-12 grid-margin stretch-card">
                    <div className="card card-default">
                        <div className="card-header cabezera">
                            <h4> Agregar Entrada </h4>
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
        
                                                        {/*
                                                            this.state.proveedores.data?
                                                            this.state.proveedores.data.map((data)=>{
                                                                
                                                               return(
                                                                <option value={data.id} > {data.nombre} </option>
                                                               ) 
                                                            }
                                                            )
                                                            :null
                                                        */}
                        
                                                    </select>                 
                                                </div>
                                            </div>
                                            <div className="col-3">
                                                <label>Numero de factura</label>
                                                <input type="text" className="form-control" name="idFactura" id="factura" onChange={this.handleChange}/>
                                            </div>
                                            <div className="col-3">
                                                <label>Fecha</label>
                                                {/*<DatePicker locale="es" selected={this.state.nowday} onChange={this.handleChangeTime} />     */} 
                                             </div>
                                                
                                        </div>
                                    </div>
                                    <div className="form-group" id="listProductos" name="this.props.number">
                                        <div className="row">
                                            <div className="col-4">
                                                <label>Producto de entrada</label><br/>
                                                <select className="form-control listProductos" id="idProducto" name="idProducto" style={{width: '100%'}} onChange={this.handleChange} value={this.state.idProducto}>
                                                    
                                                        {/*
                                                            this.state.tasks.data?
                                                            this.state.tasks.data.map((data)=>{
                                                                
                                                               return(
                                                                <option value={data.idProducto} > {data.nombreProducto} </option>
                                                               ) 
                                                            }
                                                            )
                                                            :null
                                                        */ }
                                                    
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

                                     {
                                        this.state.contador?
                                        this.state.contador.map((data)=>{
                                            
                                           return(
                                            <ListProductComponent number={data} quitarOpcion={this.quitarOpcion}/>
                                           ) 
                                        }
                                        )
                                        :null                              
                                                                    
                                     }
                                     

                                    <div className="form-group">
                                        <button type="button" id="agregarProducto" className="btn btn-gradient-warning btn-rounded btn-fw" onClick={()=>this.generateComponents()}>Agregar producto</button>
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
)
        }}
export default EntradaCrear
