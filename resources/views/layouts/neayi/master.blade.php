<!doctype html>
<html class="no-js" lang="fr">
    <head>
        <title>{{ config('app.name', 'Triple Performance') }}</title>
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,600,800">
        <link rel='stylesheet' href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script src="{{ asset('js/neayi.js') }}" defer></script>
        <link href="{{ asset('css/neayi.css') }}" rel="stylesheet">
    </head>
<body>
<div id="app">
    @include('layouts.neayi.partials.top-navbar')
    <div class="container-fluid">
        @yield('content')
    </div>
    @include('layouts.neayi.partials.footer')
    @include('users.modals.register')
</div>
</body>
</html>
