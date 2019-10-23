 <html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Styles -->
        <link href="{{ asset('css/app.css') }}" rel="stylesheet">
        <!-- plugins:css -->
        <link rel="stylesheet" href="{{ asset('assetsAdminTemplate/vendors/iconfonts/mdi/css/materialdesignicons.min.css')}}">
        <link rel="stylesheet" href="{{ asset('assetsAdminTemplate/vendors/css/vendor.bundle.base.css')}}">
        <!-- endinject -->
        <!-- inject:css -->
        <link rel="stylesheet" href="{{ asset('assetsAdminTemplate/css/style.css')}}">
        <!-- endinject -->
        <link rel="shortcut icon" href="{{ asset('assetsAdminTemplate/images/favicon.png')}}" />
        <!-- Datatables -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
        
        <!-- Select2 -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
        <!-- Toastr -->
        <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
        <!-- JQUERY Confirm -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
        <!-- JQUERY DATE RANGE -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        
         


    </head>
    <body>
        <div id="app"></div>

        <script src="{{ asset('js/app.js') }}"></script>
        <script> 
        let csrf_token = '{{ csrf_token() }}';
        </script>

    </body>
    </html>