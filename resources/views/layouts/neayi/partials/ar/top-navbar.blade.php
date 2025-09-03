<nav class="navbar navbar-expand-lg navbar-light sticky-top">
  <a class="navbar-brand" href="{{$wikiUrl}}/"><img src="{{$wikiUrl}}/skins/skin-neayi/favicon/logo-triple-performance.svg" alt="Wiki Triple Performance"></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_productions" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">الإنتاج</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Grandes cultures" title="المحاصيل الحقلية">المحاصيل الحقلية</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Élevage" title="التربية">التربية</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Viticulture" title="زراعة الكروم">زراعة الكروم</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Arboriculture" title="زراعة الأشجار">زراعة الأشجار</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Maraîchage" title="البستنة التسويقية">البستنة التسويقية</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/PPAM" title="النباتات العطرية والطبية">النباتات العطرية والطبية</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Retours_d%27expérience" title="البحث عن المزارع">البحث عن المزارع</a>

		</div>
	</li>

                    <li class="nav-item dropdown neayi-desktop-nav">
		<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown_thematiques" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">الموضوعات</a>
		<div class="dropdown-menu" aria-labelledby="navbarDropdown">
            <a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agriculture de précision" title="الزراعة الدقيقة">الزراعة الدقيقة</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agroforesterie" title="الزراعة الحرجية">الزراعة الحرجية</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Bioagresseurs" title="العدوى البيولوجية">العدوى البيولوجية</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Biodiversité" title="التنوع البيولوجي">التنوع البيولوجي</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Couverts végétaux" title="الغطاء النباتي">الغطاء النباتي</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Cycle du carbone et GES" title="دورة الكربون والغازات المسببة للانحباس الحراري العالمي">دورة الكربون والغازات المسببة للانحباس الحراري العالمي</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Désherbage" title="إزالة الأعشاب الضارة">إزالة الأعشاب الضارة</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion_d’entreprise" title="إدارة الأعمال">إدارة الأعمال</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Gestion de l’eau" title="إدارة المياه">إدارة المياه</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Matériel et machines agricoles" title="المعدات والآلات الزراعية">المعدات والآلات الزراعية</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Outils de mesure et d’observation" title="أدوات القياس والمراقبة">أدوات القياس والمراقبة</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Production énergétique" title="إنتاج الطاقة">إنتاج الطاقة</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Protection intégrée des cultures" title="الحماية المتكاملة للمحاصيل">الحماية المتكاملة للمحاصيل</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Résilience climatique" title="المرونة المناخية">المرونة المناخية</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Agronomie des sols vivants" title="التربة الحية">التربة الحية</a>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Valorisation de la production" title="تقييم الإنتاج">تقييم الإنتاج</a>
<div class="dropdown-divider"></div>
<a class="dropdown-item" href="{{$wikiUrl}}/wiki/Search" title="البحث">البحث</a>

		</div>
	</li>

            <li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Productions" title="الإنتاج">الإنتاج</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" href="{{$wikiUrl}}/wiki/Thématiques" title="الموضوعات">الموضوعات</a></li>
<li class="nav-item"><a class="nav-link" href="https://ar.forum.tripleperformance.ag" title="المنتدى">المنتدى</a></li>
<li class="nav-item"><a class="nav-link" href="{{$wikiUrl}}/wiki/الدورات التدريبية" title="الدورات التدريبية">الدورات التدريبية</a></li>
<li class="nav-item neayi-additional-mobile-menus"><a class="nav-link" onclick="window.scrollTo(0, 0); $('#searchInput').focus(); return true" data-toggle="collapse" href="#" title="البحث">البحث</a></li>

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
