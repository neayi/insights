<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_productions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Produkciók</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Szántóföldi növények">Szántóföldi növények</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Tenyésztés">Tenyésztés</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Szőlőtermesztés">Szőlőtermesztés</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Faiskola">Faiskola</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Zöldségtermesztés">Zöldségtermesztés</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="Hmp">Hmp</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="Farmok keresése">Farmok keresése</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Témák</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Precíziós mezőgazdaság">Precíziós mezőgazdaság</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Agroerdészet">Agroerdészet</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioagresszorok">Bioagresszorok</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Biodiverzitás">Biodiverzitás</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="Fedezetek">Fedezetek</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Szénciklus és ÜHG">Szénciklus és ÜHG</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="Gyomlálás">Gyomlálás</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion_d’entreprise" title="Üzletvezetés">Üzletvezetés</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Vízgazdálkodás">Vízgazdálkodás</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Mezőgazdasági berendezések és gépek">Mezőgazdasági berendezések és gépek</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Mérő és megfigyelő eszközök">Mérő és megfigyelő eszközök</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Energiatermelés">Energiatermelés</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Integrált növényvédelem">Integrált növényvédelem</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Klíma-reziliencia">Klíma-reziliencia</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="Élő-talajok">Élő-talajok</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Termelés értékelése">Termelés értékelése</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="Keresés">Keresés</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Produkciók">Produkciók</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Témák">Témák</a></li>
<li class="nav-item"><a class="nav-link" href="https://hu.forum.tripleperformance.ag" title="Fórum">Fórum</a></li>
<li class="nav-item"><a class="nav-link" href="{{$wikiUrl}}/wiki/Képzések" title="Képzések">Képzések</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" onclick="window.scrollTo(0, 0); $('#searchInput').focus(); return true" data-toggle="collapse" href="#" title="Keresés">Keresés</a></li>

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
