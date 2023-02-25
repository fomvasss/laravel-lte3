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
    <link rel="manifest" href="/vendor/lte3/img/favicons/site.webmanifest">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="/vendor/adminlte/plugins/flag-icon-css/css/flag-icon.min.css">
    <!-- Toastr -->
    <link rel="stylesheet" href="/vendor/adminlte/plugins/toastr/toastr.min.css">

    <link rel="stylesheet" href="/vendor/lte3/main.css">
    @stack('styles')

    <!-- Theme style -->
    <link rel="stylesheet" href="/vendor/adminlte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- <body class="dark-mode sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed" style="height: auto;"> -->
