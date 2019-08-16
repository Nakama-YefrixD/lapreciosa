<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>
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
  
  <!-- Datepicker -->
  <link rel="stylesheet" href="{{ asset('assetsAdminTemplate/css/bootstrap-datepicker.css')}}">
  <!-- Select2 -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.8/css/select2.min.css" rel="stylesheet" />
  <!-- Toastr -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet" />
  <!-- JQUERY Confirm -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
</head>