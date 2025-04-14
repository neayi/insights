<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_productions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Produções</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="Culturas arvenses">Culturas arvenses</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="Criação">Criação</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="Viticultura">Viticultura</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="Arboricultura">Arboricultura</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="Horticultura de mercado">Horticultura de mercado</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="Hmp">Hmp</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="Procure por quintas">Procure por quintas</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Temas</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="Agricultura de precisão">Agricultura de precisão</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="Agroflorestação">Agroflorestação</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="Bioagressores">Bioagressores</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="Biodiversidade">Biodiversidade</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="Coberturas">Coberturas</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="Ciclo do carbono e GEE">Ciclo do carbono e GEE</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="Monda">Monda</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion_d’entreprise" title="Gestão empresarial">Gestão empresarial</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="Gestão da água">Gestão da água</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="Equipamento e maquinaria agrícola">Equipamento e maquinaria agrícola</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="Ferramentas de medição e observação">Ferramentas de medição e observação</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="Produção de energia">Produção de energia</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="Proteção integrada das culturas">Proteção integrada das culturas</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="Resistência ao clima">Resistência ao clima</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="Solos vivos">Solos vivos</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="Avaliação da produção">Avaliação da produção</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="Pesquisar">Pesquisar</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="Produções">Produções</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="Temas">Temas</a></li>
<li class="nav-item"><a class="nav-link" href="https://pt.forum.tripleperformance.ag" title="Fórum">Fórum</a></li>
<li class="nav-item"><a class="nav-link" href="{{$wikiUrl}}/wiki/Cursos de formação" title="Cursos de formação">Cursos de formação</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" onclick="window.scrollTo(0, 0); $('#searchInput').focus(); return true" data-toggle="collapse" href="#" title="Pesquisar">Pesquisar</a></li>

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
