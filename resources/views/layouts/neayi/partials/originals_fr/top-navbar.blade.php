<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item"><a href="{{$wikiUrl}}/wiki/Triple_Performance" title="Accueil général [alt-shift-z]" accesskey="z" class="nav-link"><i class="fa fa-home" aria-hidden="true"></i>Accueil</a></li>

            <li class="nav-item dropdown" id="neayi-nav-other">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">Productions</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Grandes cultures">Grandes cultures</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Élevage">Élevage</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Viticulture">Viticulture</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Arboriculture">Arboriculture</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Maraîchage">Maraîchage</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="PPAM">PPAM</a>
                </div>
            </li>


            <li class="nav-item dropdown" id="neayi-nav-other">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Thématiques</a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Agriculture de précision">Agriculture de précision</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Agroforesterie">Agroforesterie</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioagresseurs">Bioagresseurs</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Biodiversité">Biodiversité</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Collaboration et coopération en agriculture" title="Collaboration et coopération en agriculture">Collaboration et coopération en agriculture</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverture des sols" title="Couverts végétaux">Couverts végétaux</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Cycle du carbone et GES">Cycle du carbone et GES</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Desherbage" title="Desherbage">Desherbage</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Diagnostique et performance d'une ferme" title="Diagnostique et performance">Diagnostique et performance</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Gestion de l’eau">Gestion de l’eau</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Matériel et machines agricoles">Matériel et machines agricoles</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Organisation du travail" title="Organisation du travail">Organisation du travail</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Outils de mesure et d’observation">Outils de mesure et d’observation</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Production énergétique">Production énergétique</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Protection intégrée des cultures">Protection intégrée des cultures</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Résilience climatique">Résilience climatique</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/S'installer en agriculture" title="S'installer">S'installer</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="Sols vivants">Sols vivants</a>
                    <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Valorisation de la production">Valorisation de la production</a>
                </div>
            </li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Productions">Productions</a></li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Thématiques">Thématiques</a></li>

            <li class="nav-item"><a class="nav-link" href="{{config('neayi.forum_url')}}" title="Forum">Forum</a></li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Aide:Créer_une_page">Ajouter une page</a></li>
        </ul>
        @if(\Illuminate\Support\Facades\Auth::user() !== null)
            <div class="create-profile">
                <div class="row align-items-center" style="height: 100%; margin: 0">
                    <div class="col-auto"><img class="neayi-avatar" src="{{\Illuminate\Support\Facades\Auth::user()->adminlte_image()}}"></div>
                    <div class="col">
                        <div class="navbar-tool dropdown position-static show" id="neayi-navbar-menu"><a href="#" class="neayi-username dropdown-toggle" data-toggle="dropdown" data-boundary="viewport">{{\Illuminate\Support\Facades\Auth::user()->fullname()}}</a><div class="p-personal-tools dropdown-menu">
                            <div id="pt-userpage"><a href="/profile" dir="auto" title="Votre page d’utilisateur [alt-shift-.]" accesskey=".">{{\Illuminate\Support\Facades\Auth::user()->fullname()}}</a></div>
                            <div id="pt-notifications-alert"><a href="{{$wikiUrl}}/wiki/Sp%C3%A9cial:Notifications" class="mw-echo-notifications-badge mw-echo-notification-badge-nojs oo-ui-icon-bell mw-echo-unseen-notifications" data-counter-num="7" data-counter-text="7" title="Vos alertes">Alertes (7)</a></div>
                            <div id="pt-notifications-notice"><a href="{{$wikiUrl}}/wiki/Sp%C3%A9cial:Notifications" class="mw-echo-notifications-badge mw-echo-notification-badge-nojs oo-ui-icon-tray mw-echo-unseen-notifications" data-counter-num="4" data-counter-text="4" title="Vos notifications">Notifications (4)</a></div>
                            <div id="pt-adminlinks"><a href="{{$wikiUrl}}/wiki/Sp%C3%A9cial:AdminLinks">Liens d’administration</a></div>
                            <div id="pt-preferences"><a href="{{$wikiUrl}}/wiki/Sp%C3%A9cial:Pr%C3%A9f%C3%A9rences" title="Vos préférences">Préférences</a></div>
                            <div id="pt-watchlist"><a href="{{$wikiUrl}}/wiki/Sp%C3%A9cial:Liste_de_suivi" title="Une liste des pages dont vous suivez les modifications [alt-shift-l]" accesskey="l">Liste de suivi</a></div>
                            <div id="pt-logout"><a href="{{$wikiUrl}}/index.php?title=Sp%C3%A9cial:D%C3%A9connexion&amp;returnto=%C3%80+propos" data-mw="interface" title="Se déconnecter">Se déconnecter</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</nav>
