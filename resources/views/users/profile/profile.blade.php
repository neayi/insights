@extends('layouts.neayi.master')

@section('title', __('pages.profile'))

@section('content')
    <div class="container profile empty">
        <div class="row py-5">
            <div class="col-md-12">

                <!-- hero -->
                <div class="row align-items-center">
                    <div class="col-md-3 d-none d-md-block">
                    <input type="file" id="fileinput" name="picture" style="display: none;"/>
                    @if(empty($user['url_picture']))
                            <a href="#" class="text-decoration-none">
                                <div class="rounded-circle empty-avatar grey-bg">
                                    <div class="initials">
                                        {{ strtoupper(substr($user['firstname'], 0, 1).substr($user['lastname'], 0, 1)) }}
                                    </div>
                                    <span class="material-icons text-dark-green">
                                        add
                                    </span>
                                    <span class="add-avatar picture_upload">
                                        Ajouter une photo
                                    </span>
                                </div>
                            </a>
                        @else
                            <div class="avatar-block">
                                <img src="{{ $user['url_picture'] }}" alt="Avatar auteur" class="rounded-circle avatar picture_upload">
                                <a class="btn btn-outline-gray edit-btn mr-2">
                                 <span class="material-icons text-dark-green picture_upload">
                                     edit
                                </span>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-9 editable">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <h2 class="d-inline-block mb-0">{{$context['fullname']}}</h2>
                                    <div class="edit d-inline-block ml-4 mt-1" data-toggle="modal" data-target="#headerEdit">
                                        <a class="btn btn-outline-gray edit-btn mr-2">
                                         <span class="material-icons text-dark-green">
                                             edit
                                        </span>
                                        </a>
                                        <a class="text-dark-green edit-link text-decoration-none">
                                            Modifier
                                        </a>
                                    </div>
                                </div>
                                <div class="secteur font-weight-semibold">@lang('wiki_profile.'.$role) {{ !empty($context['sector']) ? '- '.ucfirst($context['sector']) : '' }}</div>
                                <div class="rattachement">{{ !empty($context['structure']) ? 'Rattaché à '.ucfirst($context['structure']) : '' }}</div>
                                <div class="dropdown mt-3">
                                    <button class="btn btn-outline-gray dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                     <span class="material-icons mr-2">
                                        settings
                                     </span>
                                        Mes paramètres
                                    </button>
                                    <div class="dropdown-menu pb-0" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item pl-3" href="#">
                                        <span class="material-icons mr-2">
                                        vpn_key
                                        </span>
                                            Modifier mon mot de passe
                                        </a>
                                        <a class="dropdown-item pl-3" href="#">
                                        <span class="material-icons mr-2">
                                        notifications
                                        </span>
                                            Paramètres des notifications
                                        </a>
                                        <a class="dropdown-item pl-3" href="#">
                                        <span class="material-icons mr-2">
                                        help_outline
                                        </span>
                                            Aide
                                        </a>
                                        <a class="dropdown-item pl-3" href="#">
                                        <span class="material-icons mr-2">
                                        settings
                                        </span>
                                            Paramètres avancés
                                        </a>
                                        <a class="dropdown-item pl-3 text-danger" href="#">
                                        <span class="material-icons mr-2">
                                        exit_to_app
                                        </span>
                                            Se déconnecter
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 map">
                                <img src="{{ asset('images/map-france/544px-France_Climat_Département_'.$context['department'].'.png') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- caractéristiques -->
                <div class="row mt-4">
                    <div class="col-md-12 editable">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex align-items-center">
                                    <h3 class="font-weight-bold d-inline-block">Mes caractéristiques sur mon exploitation</h3>
                                    <div class="edit d-inline-block ml-4 mb-1" data-toggle="modal" data-target="#caracteristiquesEdit">
                                        <a class="btn btn-outline-gray edit-btn mr-2">
                                         <span class="material-icons text-dark-green">
                                             edit
                                        </span>
                                        </a>
                                        <a class="text-dark-green edit-link text-decoration-none">
                                            Modifier
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="resume-exploitation w-100">
                                    <div class="resume-exploitation-container border-0">
                                        <div class="d-flex flex-wrap">
                                            @foreach($characteristics as $characteristic)
                                                @php $characteristic = $characteristic->toArray() @endphp
                                                <div class="caracteristique-exploitation">
                                                    <img src="{{ $characteristic['icon'] }}">
                                                    <span>{{ $characteristic['caption'] }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- mes exploitations -->
                <div class="row mt-4">
                    <div class="col-md-6 editable exploitations-objectifs edition">
                        <div class="d-flex align-items-center">
                            <h3 class="font-weight-bold d-inline-block">Mon exploitation, mes objectifs</h3>
                            <div class="edit d-inline-block ml-4 mb-1"  data-toggle="modal" data-target="#exploitationsEdit">
                                <a class="btn btn-outline-gray edit-btn mr-2">
                             <span class="material-icons text-dark-green">
                                 edit
                            </span>
                                </a>
                                <a class="text-dark-green edit-link text-decoration-none">
                                    Editer
                                </a>
                            </div>
                        </div>
                        <p class="empty d-none">
                            Présentez à la communauté votre exploitation, son histoire et vos objectifs.
                        </p>
                        <p class="filled" id="dev-description">
                            {{ $context['description'] }}
                        </p>
                    </div>
                    <div class="col-md-6 editable pratiques edition">
                        <div class="d-flex align-items-center">
                            <h3 class="font-weight-bold d-inline-block">Mes pratiques</h3>
                            <div class="edit d-inline-block ml-4 mb-1">
                                <a class="btn btn-outline-gray edit-btn mr-2" data-toggle="modal" data-target="#pratiquesEdit">
                             <span class="material-icons text-dark-green">
                                 edit
                            </span>
                                </a>
                                <a class="text-dark-green edit-link text-decoration-none">
                                    Editer
                                </a>
                            </div>
                        </div>
                        <p class="empty d-none">Renseignez les pratiques misent en œuvre sur votre exploitation.</p>
                        <div class="timeline filled">
                            <div class="year">2021</div>
                            <ul class="elements">
                                <li>
                                    <a href="#">
                                        Activité biologique des sols
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter la conduite des cultures au débouché visé
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter la dose de traitement au volume foliaire en verger
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter la puissance du tracteur aux outils utilisés et à la charge
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter le système de culture en fonction du risque de salissement qu'il génère
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter la conduite des cultures au débouché visé
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter la dose de traitement au volume foliaire en verger
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter la puissance du tracteur aux outils utilisés et à la charge
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter le système de culture en fonction du risque de salissement qu'il génère
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter la conduite des cultures au débouché visé
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter la dose de traitement au volume foliaire en verger
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter la puissance du tracteur aux outils utilisés et à la charge
                                    </a>
                                </li>
                                <li>
                                    <a href="#">
                                        Adapter le système de culture en fonction du risque de salissement qu'il génère
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <a class="btn btn-outline-gray mt-2">
                            Afficher toutes mes pratiques
                        </a>
                    </div>
                </div>

                <!-- mon activité -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h3 class="font-weight-bold mb-3">Mon activité</h3>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item mr-1" role="presentation">
                                <a class="nav-link px-4 active" id="following-tab" data-toggle="tab" href="#following" role="tab" aria-controls="home" aria-selected="true">
                                    Pages suivies
                                </a>
                            </li>
                            <li class="nav-item mr-1" role="presentation">
                                <a class="nav-link px-4" id="comments-tab" data-toggle="tab" href="#comments" role="tab" aria-controls="comments" aria-selected="false">
                                    Commentaires
                                </a>
                            </li>
                            <li class="nav-item" role="presentation">
                                <a class="nav-link px-4" id="third-tab" data-toggle="tab" href="#third" role="tab" aria-controls="contact" aria-selected="false">
                                    Pages applaudies
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active following" id="following" role="tabpanel" aria-labelledby="following-tab">
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <p class="empty d-none">Accédez rapidement à toutes les pages que souhaitez mettre de côté dans cette section.</p>
                                        <a class="btn btn-outline-gray">
                                         <span class="material-icons">
                                             sort
                                        </span>
                                            Dernières pages suivies
                                        </a>
                                        <a class="btn btn-outline-gray ml-2 py-2">
                                         <span class="material-icons">
                                             search
                                        </span>
                                        </a>
                                        <div class="edit d-inline-block ml-2 py-2" data-toggle="modal" data-target="#followingEdit">
                                            <a class="btn btn-outline-gray edit-btn mr-2">
                                             <span class="material-icons text-dark-green">
                                                 edit
                                            </span>
                                            </a>
                                            <a class="text-dark-green edit-link text-decoration-none">
                                                Editer
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <!--followed page -->
                                    <div class="col-lg-6 followed-page mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-5">
                                                <img src="assets/background-hero.jpg" class="followed-image mb-lg-0 mb-3">
                                            </div>
                                            <div class="col-lg-7">
                                                <h4>Gérer les populations des bioagresseurs grâce aux mesures prophylactiques</h4>
                                                <div class="applause-count d-inline-block">
                                                    67
                                                    <img src="assets/applause.png">
                                                </div>
                                                <span class="badge badge-grey">
                                                Bioagresseurs
                                            </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!--followed page -->
                                    <div class="col-lg-6 followed-page mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-5">
                                                <img src="assets/background-hero2.jpg" class="followed-image mb-lg-0 mb-3">
                                            </div>
                                            <div class="col-lg-7">
                                                <h4>Implanter des bandes herbeuses et florales en bordure des parcelles</h4>
                                                <div class="applause-count d-inline-block">
                                                    52
                                                    <img src="assets/applause.png">
                                                </div>
                                                <span class="badge badge-grey">
                                                Bordures
                                            </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!--followed page -->
                                    <div class="col-lg-6 followed-page mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-5">
                                                <img src="assets/background-hero.jpg" class="followed-image mb-lg-0 mb-3">
                                            </div>
                                            <div class="col-lg-7">
                                                <h4>Gérer les populations des bioagresseurs grâce aux mesures prophylactiques</h4>
                                                <div class="applause-count d-inline-block">
                                                    67
                                                    <img src="assets/applause.png">
                                                </div>
                                                <span class="badge badge-grey">
                                                Bioagresseurs
                                            </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!--followed page -->
                                    <div class="col-lg-6 followed-page mb-3">
                                        <div class="row align-items-center">
                                            <div class="col-lg-5">
                                                <img src="https://wiki.tripleperformance.fr/images/b/b6/Georges-Joya.jpg" class="followed-image mb-lg-0 mb-3">
                                            </div>
                                            <div class="col-lg-7">
                                                <h4>Implanter des bandes herbeuses et florales en bordure des parcelles</h4>
                                                <div class="applause-count d-inline-block">
                                                    52
                                                    <img src="assets/applause.png">
                                                </div>
                                                <span class="badge badge-grey">
                                                Bordures
                                            </span>
                                            </div>
                                        </div>
                                    </div>


                                </div>

                            </div>
                            <div show-async="{{ route('profile.comments.show') }}" class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                            </div>
                            <div class="tab-pane fade" id="third" role="tabpanel" aria-labelledby="third-tab">...</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('users.profile.modals.add-characteristics')
    @include('users.profile.modals.characteristics-edit')
    @include('users.profile.modals.farming-edit')
    @include('users.profile.modals.following-pages')
    @include('users.profile.modals.header-edit')
    @include('users.profile.modals.my-practices')
@endsection


