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
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Field crops">Field crops</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Breeding">Breeding</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Viticulture">Viticulture</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Arboriculture">Arboriculture</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Market gardening">Market gardening</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="HMP">HMP</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="Search for case studies">Search for case studies</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Portraits_de_ferme" title="Search for portraits">Search for portraits</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Themes</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Precision agriculture">Precision agriculture</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Agroforestry">Agroforestry</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioaggressors">Bioaggressors</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Biodiversity">Biodiversity</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Collaboration et coopération en agriculture" title="Collaboration and cooperation in agriculture">Collaboration and cooperation in agriculture</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="Plant cover">Plant cover</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Carbon cycle and GHG">Carbon cycle and GHG</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="Weeding">Weeding</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Diagnostic et performance d'une ferme" title="Diagnosis and performance">Diagnosis and performance</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Water management">Water management</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Agricultural equipment and machinery">Agricultural equipment and machinery</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Measurement and observation tools">Measurement and observation tools</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Energy production">Energy production</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Integrated crop protection">Integrated crop protection</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Climate resilience">Climate resilience</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/S'installer en agriculture" title="Settling down">Settling down</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Transmettre sa ferme" title="Selling the farm">Selling the farm</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="Living soils">Living soils</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Valuation of production">Valuation of production</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="Search">Search</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Productions">Productions</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Themes">Themes</a></li>
<li class="nav-item" id="forum-nav"><a class="nav-link" href="https://forum.es.tripleperformance.ag" title="Forum">Forum</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="https://forum.es.tripleperformance.ag" title="Forum">Forum</a></li>

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
