<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_productions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Producciones</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Grandes culturas">Grandes culturas</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Cría">Cría</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Viticultura">Viticultura</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Arboricultura">Arboricultura</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Horticultura">Horticultura</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="PMAP">PMAP</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="Búsqueda de casos prácticos">Búsqueda de casos prácticos</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Portraits_de_ferme" title="Buscar retratos">Buscar retratos</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Temas</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Agricultura de precisión">Agricultura de precisión</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Agroforestería">Agroforestería</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioagresores">Bioagresores</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Biodiversidad">Biodiversidad</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="Cubierta vegetal">Cubierta vegetal</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Ciclo del carbono y GEI">Ciclo del carbono y GEI</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="Deshierbe">Deshierbe</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion_d’entreprise" title="Gestión empresarial">Gestión empresarial</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Administracion del Agua">Administracion del Agua</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Equipos y maquinaria agrícola">Equipos y maquinaria agrícola</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Herramientas de medición y observación.">Herramientas de medición y observación.</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Producción de energía">Producción de energía</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Protección integral de cultivos">Protección integral de cultivos</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Resiliencia climática">Resiliencia climática</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="Suelos vivos">Suelos vivos</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Valoración de la producción">Valoración de la producción</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="Buscar en">Buscar en</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Producciones">Producciones</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Temas">Temas</a></li>
<li class="nav-item"><a class="nav-link" href="https://es.forum.tripleperformance.ag" title="Foro">Foro</a></li>
<li class="nav-item"><a class="nav-link" href="{{$wikiUrl}}/wiki/Cursos de formación" title="Cursos de formación">Cursos de formación</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" onclick="window.scrollTo(0, 0); $('#searchInput').focus(); return true" data-toggle="collapse" href=".navbar-collapse" title="Buscar en">Buscar en</a></li>

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
