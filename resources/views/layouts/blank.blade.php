@include('partials.head')
@include('partials.topnav')
@include('partials.sidebar')
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      @yield('content')
    </div>
  </div>
</div>
</div>
</div>

@include('partials.footer')

</div>
  </div>
  <script src="{{ asset('assetsAdminTemplate/vendors/js/vendor.bundle.base.js')}}"></script>
  <script src="{{ asset('assetsAdminTemplate/vendors/js/vendor.bundle.addons.js')}}"></script>
  <!-- endinject -->
  <!-- Plugin js for this page-->
  <!-- End plugin js for this page-->
  <!-- inject:js -->
  <script src="{{ asset('assetsAdminTemplate/js/off-canvas.js')}}"></script>
  <script src="{{ asset('assetsAdminTemplate/js/misc.js')}}"></script>
  <!-- endinject -->
  <!-- Custom js for this page-->
  <script src="{{ asset('assetsAdminTemplate/js/dashboard.js')}}"></script>
  <!-- End custom js for this page-->
  
  <!-- DATATABLES -->
  <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
  <!-- Datepicker -->
  <script src="{{ asset('assetsAdminTemplate/js/bootstrap-datepicker.js')}}"></script>
  <!-- Select2 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/js/select2.min.js"></script>
  <!-- Toastr -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <!-- Jquery Confirm -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
  @yield('script')
</body>

</html>