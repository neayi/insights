<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_productions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Productions</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Grandes cultures">Grandes cultures</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Élevage">Élevage</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Viticulture">Viticulture</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Arboriculture">Arboriculture</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Maraîchage">Maraîchage</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="PPAM">PPAM</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="Rechercher des retours d’expérience">Rechercher des retours d’expérience</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Portraits_de_ferme" title="Rechercher des portraits de ferme">Rechercher des portraits de ferme</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Thématiques</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Agriculture de précision">Agriculture de précision</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Agroforesterie">Agroforesterie</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioagresseurs">Bioagresseurs</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Biodiversité">Biodiversité</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Collaboration et coopération en agriculture" title="Collaboration et coopération en agriculture">Collaboration et coopération en agriculture</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="Couverts végétaux">Couverts végétaux</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Cycle du carbone et GES">Cycle du carbone et GES</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="Désherbage">Désherbage</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Diagnostic et performance d'une ferme" title="Diagnostic et performance">Diagnostic et performance</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Gestion de l’eau">Gestion de l’eau</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Matériel et machines agricoles">Matériel et machines agricoles</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Outils de mesure et d’observation">Outils de mesure et d’observation</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Production énergétique">Production énergétique</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Protection intégrée des cultures">Protection intégrée des cultures</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Résilience climatique">Résilience climatique</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/S'installer en agriculture" title="S’installer">S’installer</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Transmettre sa ferme" title="Transmettre sa ferme">Transmettre sa ferme</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="Sols vivants">Sols vivants</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Valorisation de la production">Valorisation de la production</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="Rechercher">Rechercher</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Productions">Productions</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Thématiques">Thématiques</a></li>
<li class="nav-item" id="forum-nav"><a class="nav-link" href="https://forum.tripleperformance.fr" title="Forum">Forum</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="https://forum.tripleperformance.fr" title="Forum">Forum</a></li>

        </ul>
        @if(\Illuminate\Support\Facades\Auth::user() !== null)
            <div class="create-profile">
                <div class="row align-items-center" style="height: 100%; margin: 0">
                    <div class="col-auto"><img class="neayi-avatar" src="{{\Illuminate\Support\Facades\Auth::user()->adminlte_image()}}"></div>
                    <div class="col">
                        <div class="navbar-tool dropdown position-static show" id="neayi-navbar-menu">
                            <a href="#" class="neayi-username dropdown-toggle" data-toggle="dropdown" data-boundary="viewport">{{\Illuminate\Support\Facades\Auth::user()->fullname()}}</a>
                            <div class="p-personal-tools dropdown-menu">
                                <div id="pt-userpage"><a href="/profile" dir="auto" title="Votre page d’utilisateur [alt-shift-.]" accesskey=".">{{\Illuminate\Support\Facades\Auth::user()->fullname()}}</a></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</nav>
