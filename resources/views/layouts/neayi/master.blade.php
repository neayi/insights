<!doctype html>
<html class="no-js" lang="fr">
    <head>
        <title>@yield('title') - Triple Performance</title>
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Raleway:400,600,800">
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="apple-touch-icon" href="https://wiki.tripleperformance.fr/skins/skin-neayi/favicon/apple-touch-icon.png"/>
        <link rel="shortcut icon" href="https://wiki.tripleperformance.fr/skins/skin-neayi/favicon/favicon.ico"/>
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <script src="{{ asset('js/neayi.js') }}" defer></script>
        <link href="{{ asset('css/neayi.css') }}" rel="stylesheet">
    </head>
<body>
    <div id="app">

        @php
            $currentUser = \Illuminate\Support\Facades\Auth::user();

            if (!empty($currentUser)) {
                $wikiUrl = $currentUser->locale()->wiki_url;
                $lang = $currentUser->wiki;
            }
            else
            {
                $locale = \App\LocalesConfig::getPreferredLocale();
                $lang = $locale->code;
                $wikiUrl = $locale->wiki_url;
            }

        @endphp

        @include('layouts.neayi.partials.'.$lang.'.top-navbar', ['wikiUrl' => $wikiUrl])
        <div class="container-fluid">
            @yield('content')
        </div>

        @include('layouts.neayi.partials.'.$lang.'.footer', ['wikiUrl' => $wikiUrl])
        @include('users.modals.register')
    </div>

    @include('layouts.neayi.partials.google-analitycs')
</body>
</html>
