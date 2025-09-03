<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_productions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Tuotokset</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Peltokasvit">Peltokasvit</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Kasvatus">Kasvatus</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Viininviljely">Viininviljely</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Puutarhaviljely">Puutarhaviljely</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Kauppapuutarhanhoito">Kauppapuutarhanhoito</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="Lääkekasvit ja Aromikasvit">Lääkekasvit ja Aromikasvit</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="Etsi maatiloja">Etsi maatiloja</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Teemat</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Tarkkuusmaatalous">Tarkkuusmaatalous</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Maatalousmetsätalous">Maatalousmetsätalous</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioaggressorit">Bioaggressorit</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Biologinen monimuotoisuus">Biologinen monimuotoisuus</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="Kasvipeite">Kasvipeite</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Hiilikierto ja kasvihuonekaasut">Hiilikierto ja kasvihuonekaasut</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="Kitkeminen">Kitkeminen</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion_d’entreprise" title="Liiketoiminnan johtaminen">Liiketoiminnan johtaminen</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Vesihuolto">Vesihuolto</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Equipamentos e máquinas agrícolas">Equipamentos e máquinas agrícolas</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Ferramentas de medição e observação">Ferramentas de medição e observação</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Energian tuotanto">Energian tuotanto</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Integroitu kasvinsuojelu">Integroitu kasvinsuojelu</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Ilmaston sietokyky">Ilmaston sietokyky</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="Elävät maaperät">Elävät maaperät</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Tuotannon arvostus">Tuotannon arvostus</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="Etsi">Etsi</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Tuotokset">Tuotokset</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Teemat">Teemat</a></li>
<li class="nav-item"><a class="nav-link" href="https://fi.forum.tripleperformance.ag" title="Foorumi">Foorumi</a></li>
<li class="nav-item"><a class="nav-link" href="{{$wikiUrl}}/wiki/Koulutuskurssit" title="Koulutuskurssit">Koulutuskurssit</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" onclick="window.scrollTo(0, 0); $('#searchInput').focus(); return true" data-toggle="collapse" href="#" title="Etsi">Etsi</a></li>

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
