import React from 'react'
import { Link } from 'react-router-dom'

const Top = () => (


<div className="container-scroller">
    <nav className="navbar default-layout-navbar col-lg-12 col-12 p-0 fixed-top d-flex flex-row">
      <div className="text-center navbar-brand-wrapper d-flex align-items-center justify-content-center">
        <a className="navbar-brand brand-logo" href="#"><img src="http://localhost:8000/assetsAdminTemplate/images/preciosa.svg" alt="logo"/></a>
      </div>
      <div className="navbar-menu-wrapper d-flex align-items-stretch">
        <ul className="navbar-nav navbar-nav-right">
          <li className="nav-item d-none d-lg-block full-screen-link">
            <a className="nav-link">
              <i className="mdi mdi-fullscreen" id="fullscreen-button"></i>
            </a>
          </li>
          <li className="nav-item nav-profile dropdown">
            <a className="nav-link dropdown-toggle" id="profileDropdown" href="#" data-toggle="dropdown" aria-expanded="false">
              <div className="nav-profile-img">
                <img src="{{ asset('img/usuarios/'.Auth::user()->imagen)}}" alt="image"/>
                <span className="availability-status online"></span>             
              </div>
              <div className="nav-profile-text">
                <p className="mb-1 text-black"> quesoman</p>
              </div>
            </a>
            <div className="dropdown-menu navbar-dropdown" aria-labelledby="profileDropdown">
              
              <div className="dropdown-divider"></div>
              <a className="dropdown-item" href="{{ route('logout') }}"
                  onClick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                  
              Logoutyxd</a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style={{display: 'none'}}>
                  @csrf
              </form>
          
            </div>
          </li>
        </ul>
        <button className="navbar-toggler navbar-toggler-right d-lg-none align-self-center" type="button" data-toggle="offcanvas">
          <span className="mdi mdi-menu"></span>
        </button>
     </div>
    </nav>
 </div>
)

export default Top
