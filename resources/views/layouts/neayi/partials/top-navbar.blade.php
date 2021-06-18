<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{config('neayi.wiki_url')}}/"><img src="{{config('neayi.wiki_url')}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item"><a href="{{config('neayi.wiki_url')}}/wiki/Accueil" title="Accueil général [alt-shift-z]" accesskey="z" class="nav-link">Accueil</a></li>
          <li class="nav-item"><a href="{{config('neayi.wiki_url')}}/wiki/Sp%C3%A9cial:BrowseData/Pratiques_agro-%C3%A9cologiques" class="nav-link">Pratiques</a></li>
          <li class="nav-item"><a href="{{config('neayi.wiki_url')}}/wiki/Sp%C3%A9cial:BrowseData/Exemples_de_mise_en_%C5%93uvre" class="nav-link">Retours d'expérience</a></li>
          <li class="nav-item"><a href="{{config('neayi.wiki_url')}}/wiki/Sp%C3%A9cial:BrowseData/Vid%C3%A9os" class="nav-link">Vidéos</a></li>
        </ul>
        <div class="create-profile">
            <div class="row align-items-center" style="height: 100%; margin: 0">
                <div class="col-auto"><img class="neayi-avatar" src="{{\Illuminate\Support\Facades\Auth::user()->adminlte_image()}}"></div>
                <div class="col">
                    <div class="navbar-tool dropdown position-static show" id="neayi-navbar-menu"><a href="#" class="neayi-username dropdown-toggle" data-toggle="dropdown" data-boundary="viewport" title="Vous êtes connecté en tant que Bertrand Gorge.">{{\Illuminate\Support\Facades\Auth::user()->fullname()}}</a><div class="p-personal-tools dropdown-menu">
                        <div id="pt-userpage"><a href="{{config('neayi.wiki_url')}}/wiki/Utilisateur:Bertrand_Gorge" dir="auto" title="Votre page d’utilisateur [alt-shift-.]" accesskey=".">{{\Illuminate\Support\Facades\Auth::user()->fullname()}}</a></div>
                        <div id="pt-notifications-alert"><a href="{{config('neayi.wiki_url')}}/wiki/Sp%C3%A9cial:Notifications" class="mw-echo-notifications-badge mw-echo-notification-badge-nojs oo-ui-icon-bell mw-echo-unseen-notifications" data-counter-num="7" data-counter-text="7" title="Vos alertes">Alertes (7)</a></div>
                        <div id="pt-notifications-notice"><a href="{{config('neayi.wiki_url')}}/wiki/Sp%C3%A9cial:Notifications" class="mw-echo-notifications-badge mw-echo-notification-badge-nojs oo-ui-icon-tray mw-echo-unseen-notifications" data-counter-num="4" data-counter-text="4" title="Vos notifications">Notifications (4)</a></div>
                        <div id="pt-mytalk"><a href="{{config('neayi.wiki_url')}}/wiki/Discussion_utilisateur:Bertrand_Gorge" class="new" title="Votre page de discussion (page inexistante) [alt-shift-n]" accesskey="n">Discussion</a></div>
                        <div id="pt-adminlinks"><a href="{{config('neayi.wiki_url')}}/wiki/Sp%C3%A9cial:AdminLinks">Liens d’administration</a></div>
                        <div id="pt-preferences"><a href="{{config('neayi.wiki_url')}}/wiki/Sp%C3%A9cial:Pr%C3%A9f%C3%A9rences" title="Vos préférences">Préférences</a></div>
                        <div id="pt-watchlist"><a href="{{config('neayi.wiki_url')}}/wiki/Sp%C3%A9cial:Liste_de_suivi" title="Une liste des pages dont vous suivez les modifications [alt-shift-l]" accesskey="l">Liste de suivi</a></div>
                        <div id="pt-mycontris"><a href="{{config('neayi.wiki_url')}}/wiki/Sp%C3%A9cial:Contributions/Bertrand_Gorge" title="La liste de vos contributions [alt-shift-y]" accesskey="y">Contributions</a></div>
                        <div id="pt-logout"><a href="{{config('neayi.wiki_url')}}/index.php?title=Sp%C3%A9cial:D%C3%A9connexion&amp;returnto=%C3%80+propos" data-mw="interface" title="Se déconnecter">Se déconnecter</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>
