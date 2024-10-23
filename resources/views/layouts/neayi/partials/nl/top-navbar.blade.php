<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_productions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Producties</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Grote culturen">Grote culturen</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Fokken">Fokken</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Wijnbouw">Wijnbouw</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Boomkwekerij">Boomkwekerij</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Tuinieren">Tuinieren</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="PMAP">PMAP</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="Zoeken naar casestudies">Zoeken naar casestudies</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Portraits_de_ferme" title="Portretten zoeken">Portretten zoeken</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Thema's</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Precisie landbouw">Precisie landbouw</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Agrobosbouw">Agrobosbouw</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioagressors">Bioagressors</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Biodiversiteit">Biodiversiteit</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="Bedekking van planten">Bedekking van planten</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Koolstofcyclus en broeikasgassen">Koolstofcyclus en broeikasgassen</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="Wieden">Wieden</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion_d’entreprise" title="Bedrijfsbeheer">Bedrijfsbeheer</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Water beheersing">Water beheersing</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Landbouwwerktuigen en -machines">Landbouwwerktuigen en -machines</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Meet- en observatie-instrumenten">Meet- en observatie-instrumenten</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Energie productie">Energie productie</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Geïntegreerde gewasbescherming">Geïntegreerde gewasbescherming</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Klimaatbestendigheid">Klimaatbestendigheid</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="Levende bodems">Levende bodems</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Waardebepaling van de productie">Waardebepaling van de productie</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="Zoeken">Zoeken</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Producties">Producties</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Thema&#039;s">Thema's</a></li>
<li class="nav-item"><a class="nav-link" href="https://nl.forum.tripleperformance.ag" title="Forum">Forum</a></li>
<li class="nav-item"><a class="nav-link" href="{{$wikiUrl}}/wiki/Cursussen" title="Cursussen">Cursussen</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" onclick="window.scrollTo(0, 0); $('#searchInput').focus(); return true" data-toggle="collapse" href=".navbar-collapse" title="Zoeken">Zoeken</a></li>

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
