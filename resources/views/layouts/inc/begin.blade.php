<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }} | Dashboard</title>

    <link rel="apple-touch-icon" sizes="180x180" href="/vendor/lte3/img/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/vendor/lte3/img/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/vendor/lte3/img/favicons/favicon-16x16.png">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/vendor/adminlte/plugins/flag-icon-css/css/flag-icon.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/toastr/toastr.min.css">
    <!-- Sweetalert -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/sweetalert2/sweetalert2.min.css">
    <!-- Bootstrap Color Picker -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css">
    <!-- Select2 -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/select2/css/select2.min.css">

    <!-- PACE -->
    <link rel="stylesheet" href="/vendor/lte3/plugins/pace/pace-theme-default.min.css">
    <link rel="stylesheet" href="/vendor/lte3/plugins/pace/flash.css">
    <!-- Datetimepicker -->
    <link rel="stylesheet" href="/vendor/lte3/plugins/datepicker/datetimepicker.min.css">
    <!-- Magnific-popup -->
    <link rel="stylesheet" href="/vendor/lte3/plugins/magnific-popup/dist/magnific-popup.css">
    <!-- X-Editable -->
    <link rel="stylesheet" href="/vendor/lte3/plugins/x-editable/dist/bootstrap-editable.css">
    <!-- Select2ToTree -->
    <link rel="stylesheet" href="/vendor/lte3/plugins/select2-to-tree/src/select2totree.css">
    <!-- Bootstrap Tree View -->
    <link rel="stylesheet" href="/vendor/lte3/plugins/bootstrap-treeview/bootstrap-treeview.min.css">

    <!-- Theme style -->
    <link rel="stylesheet" href="/vendor/adminlte/dist/css/adminlte.min.css">

    <link rel="stylesheet" href="/vendor/lte3/main.css">
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed {{config('lte3.view.dark_mode') ? 'dark-mode' : ''}}">
