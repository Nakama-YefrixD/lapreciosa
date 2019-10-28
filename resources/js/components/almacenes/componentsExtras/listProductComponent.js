
import React from 'react'
import {Component} from 'react';


class ListProductComponent extends Component {
    
    constructor(){
        super();
        this.state ={

        }
    }


    render(){
        return(

                                <div className="form-group" id="listProductos" name={this.props.number}>
                                        <div className="row">
                                            <div className="col-4">
                                                <label>Producto de entrada</label><br/>
                                                <select className="form-control listProductos" id="idProducto" name="idProducto" style={{width: '100%'}} >
                                                    
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
                                                <button type="button" class="btn btn-gradient-danger btn-rounded btn-icon remove" onClick={()=>this.props.quitarOpcion(this.props.number)}><i class="mdi mdi-close"></i></button>
                                            </div>
                                        
                                        </div>
                                    </div>
         ) }}

    export default ListProductComponent