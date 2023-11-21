<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_productions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Produkcje</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Uprawy polowe">Uprawy polowe</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Hodowla">Hodowla</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Uprawa winorośli">Uprawa winorośli</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Uprawa drzew">Uprawa drzew</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Ogrodnictwo na rynku">Ogrodnictwo na rynku</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="HMP">HMP</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="Wyszukaj opinie">Wyszukaj opinie</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Portraits_de_ferme" title="Wyszukiwanie portretów rolniczych">Wyszukiwanie portretów rolniczych</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tematy</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Rolnictwo precyzyjne">Rolnictwo precyzyjne</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Agroleśnictwo">Agroleśnictwo</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioagresorzy">Bioagresorzy</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Różnorodność biologiczna">Różnorodność biologiczna</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Collaboration et coopération en agriculture" title="Kooperacja i kooperacja w rolnictwie">Kooperacja i kooperacja w rolnictwie</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="Okrywy roślinne">Okrywy roślinne</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Cykl węgla i GHG">Cykl węgla i GHG</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="Pielenie">Pielenie</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Diagnostic et performance d'une ferme" title="Diagnoza i wydajnosc">Diagnoza i wydajnosc</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Gospodarka wodna">Gospodarka wodna</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Sprzęt i maszyny rolnicze">Sprzęt i maszyny rolnicze</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Narzędzia pomiarowe i obserwacyjne">Narzędzia pomiarowe i obserwacyjne</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Produkcja energii">Produkcja energii</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Zintegrowana ochrona upraw">Zintegrowana ochrona upraw</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Odporność na klimat">Odporność na klimat</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/S'installer en agriculture" title="Osiedlenie się">Osiedlenie się</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Transmettre sa ferme" title="Sprzedaje gospodarstwo">Sprzedaje gospodarstwo</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="żywe gleby">żywe gleby</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Wycena produkcji">Wycena produkcji</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="Wyszukaj">Wyszukaj</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Produkcje">Produkcje</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Tematy">Tematy</a></li>
<li class="nav-item" id="forum-nav"><a class="nav-link" href="https://forum.pl.tripleperformance.ag" title="Forum">Forum</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="https://forum.pl.tripleperformance.ag" title="Forum">Forum</a></li>

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
