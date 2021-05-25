<!doctype html>
<html class="no-js" lang="fr">
    <head>
        <title>@yield('title')</title>
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,600,800">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script src="{{ asset('js/neayi.js') }}" defer></script>
        <link href="{{ asset('css/neayi.css') }}" rel="stylesheet">
    </head>
<body class="login">
<div id="app">
    <div class="container-fluid">
        @yield('content')
    </div>
</div>
</body>
</html>
