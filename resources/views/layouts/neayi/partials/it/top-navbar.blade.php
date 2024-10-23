<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_productions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Produzioni</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Grandi culture">Grandi culture</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Allevamento">Allevamento</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Viticoltura">Viticoltura</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Arboricoltura">Arboricoltura</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Orticoltura">Orticoltura</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="PMAP">PMAP</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="Ricerca di casi di studio">Ricerca di casi di studio</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Portraits_de_ferme" title="Ricerca di ritratti">Ricerca di ritratti</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Temi</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Agricoltura di precisione">Agricoltura di precisione</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Agroforestale">Agroforestale</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioaggressori">Bioaggressori</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Biodiversità">Biodiversità</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="Copertura vegetale">Copertura vegetale</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Ciclo del carbonio e GHG">Ciclo del carbonio e GHG</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="Diserbo">Diserbo</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion_d’entreprise" title="Gestione aziendale">Gestione aziendale</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Gestione delle risorse idriche">Gestione delle risorse idriche</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Attrezzature e macchine agricole">Attrezzature e macchine agricole</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Strumenti di misurazione e osservazione">Strumenti di misurazione e osservazione</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Produzione di energia">Produzione di energia</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Protezione integrata delle colture">Protezione integrata delle colture</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Resilienza climatica">Resilienza climatica</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="Suoli vivi">Suoli vivi</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Valutazione della produzione">Valutazione della produzione</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="Ricerca per">Ricerca per</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Produzioni">Produzioni</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Temi">Temi</a></li>
<li class="nav-item"><a class="nav-link" href="https://it.forum.tripleperformance.ag" title="Forum">Forum</a></li>
<li class="nav-item"><a class="nav-link" href="{{$wikiUrl}}/wiki/Corsi di formazione" title="Corsi di formazione">Corsi di formazione</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" onclick="window.scrollTo(0, 0); $('#searchInput').focus(); return true" data-toggle="collapse" href=".navbar-collapse" title="Ricerca per">Ricerca per</a></li>

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
