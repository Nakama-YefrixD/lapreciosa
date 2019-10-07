<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>PRECIOSA PDF</title>
    <link rel="stylesheet" href="{{public_path('pdf/style.css')}}">
  </head>
  <body>
    <header class="clearfix">
      <div id="logo">
        <img src="{{public_path('img/logo.png')}}">
      </div>
      <div id="company">
        <!-- <h2 class="name">Company Name</h2>
        <div>455 Foggy Heights, AZ 85004, US</div>
        <div>(602) 519-0450</div>
        <div><a href="mailto:company@example.com">company@example.com</a></div> -->
      </div>
      </div>
    </header>
    <main>
      <div id="details" class="clearfix">
        <div id="client">
          <div class="to">PARA:</div>
          <h2 class="name">{{ $cliente->nombre }}</h2>
          <!-- <div class="address">796 Silver Harbour, TX 79273, US</div>
          <div class="email"><a href="mailto:john@example.com">john@example.com</a></div> -->
        </div>
        <div id="invoice">
          <h1>INVOICE 3-2-1</h1>
          <div class="date">Date of Invoice: 01/06/2014</div>
          <div class="date">Due Date: 30/06/2014</div>
        </div>
      </div>
      <table border="0" cellspacing="0" cellpadding="0">
        <thead>
          <tr>
            <th class="no">#</th>
            <th class="desc">PRODUCTO</th>
            <th class="unit">PRECIO</th>
            <th class="qty">DESCUENTO</th>
            <th class="unit">CANTIDAD</th>
            <th class="total">TOTAL</th>
          </tr>
        </thead>
        <tbody>
        @foreach($detallesVenta as $detalleVenta)
          <tr>
            <td class="no">{{ $item }}</td>
            <td class="desc"><h3> {{ $detalleVenta->nombreProducto }}</h3></td>
            <td class="unit">{{ $detalleVenta->precioProducto }}</td>
            <td class="qty">{{ $detalleVenta->descuentoProducto}}</td>
            <td class="unit">{{ $detalleVenta->cantidadProducto}}</td>
            <td class="total">{{ $detalleVenta->totalProducto }}</td>
          </tr>
          @php
                $item++
            @endphp
        @endforeach
          
        </tbody>
        <tfoot>
          <tr>
            <td colspan="3"></td>
            <td colspan="2">DESCUENTO</td>
            <td>{{ $venta->descuento }}</td>
          </tr>
          <tr>
            <td colspan="3"></td>
            <td colspan="2">SUBTOTAL</td>
            <td>{{ $venta->subtotal }}</td>
          </tr>
          <tr>
            <td colspan="3"></td>
            <td colspan="2">IGV 18%</td>
            <td>{{ $venta->impuestos }}</td>
          </tr>
          
          <tr>
            <td colspan="3"></td>
            <td colspan="2">TOTAL</td>
            <td>{{ $venta->total }}</td>
          </tr>
        </tfoot>
      </table>
      <div id="thanks">Gracias!</div>
      <div id="notices">
        <div>Observacion:</div>
        <div class="notice">{{ $venta->observaciones }}</div>
      </div>
    </main>
    <footer>
      PRECIOSA.
    </footer>
  </body>
</html>