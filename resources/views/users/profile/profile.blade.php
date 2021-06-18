@extends('layouts.neayi.master')

@section('title', __('pages.profile'))

@section('content')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    <div class="container profile empty">
        <div class="row py-5">
            <div class="col-md-12">

                <!-- hero -->
                <div class="row">
                    <div class="col-md-3 d-none d-md-block editable">
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
                                <a class="btn btn-outline-gray edit-btn mr-2 edit-link">
                                    <span class="edit-link-stylus material-icons text-dark-green picture_upload">
                                        edit
                                    </span>

                                    <form action="{{ route('user.delete.avatar') }}" method="POST">
                                        @csrf
                                        <span id="btn-remove-avatar" class="edit-link-stylus material-icons text-dark-green">
                                            delete
                                        </span>
                                    </form>
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-9 editable">
                        <div class="row position-relative">
                            <div class="col-md-8 position-static">
                                <div class="d-flex align-items-center">
                                    <h2 class="d-inline-block mb-0">{{$context['fullname']}}</h2>
                                    <div class="edit d-inline-block ml-4 mt-1" data-toggle="modal" data-target="#headerEdit">
                                        <a class="text-dark-green stretched-link edit-link text-decoration-none">
                                           <span class="edit-link-stylus material-icons text-dark-green">edit</span>
                                           <span class="edit-link-label">Modifier</span>
                                        </a>
                                    </div>
                                </div>
                                <div class="secteur font-weight-semibold">@lang('wiki_profile.'.$role) {{ !empty($context['sector']) ? '- '.ucfirst($context['sector']) : '' }} {{ !empty($context['structure']) ? ' ('.ucfirst($context['structure']) .')' : '' }}</div>

                                <!--div class="dropdown mt-3">
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
                                </div-->
                            </div>
                            <div class="col-md-4 map">
                                <img src="{{ asset('images/map-france/France Climat Département '.$context['department'].'.svg') }}">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- caractéristiques -->
                <div class="row mt-4">
                    <div class="col-md-12 editable">
                        <div class="row position-relative">
                            <div class="col-12 position-static">
                                <div class="d-flex align-items-center">
                                    <h3 class="font-weight-bold d-inline-block">Mes caractéristiques sur mon exploitation</h3>
                                    <div class="edit d-inline-block ml-4 mb-1" data-toggle="modal" data-target="#caracteristiquesEdit">
                                        <a class="edit-link stretched-link btn btn-outline-gray edit-btn mr-2">
                                            <span class="edit-link-stylus material-icons text-dark-green">edit</span>
                                            <span class="edit-link-label text-dark-green">Modifier</span>
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
                                                    <a class="stretched-link" href="{{config('neayi.wiki_url')}}/wiki/{{ $characteristic['page'] }}" target="_blank"><span>{{ $characteristic['caption'] }}</span></a>
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
                                <a class="edit-link btn btn-outline-gray edit-btn mr-2">
                                    <span class="edit-link-stylus material-icons text-dark-green">edit</span>
                                    <span class="edit-link-label text-dark-green">Modifier</span>
                                </a>
                            </div>
                        </div>
                        <p class="empty d-none">
                            Présentez à la communauté votre exploitation, son histoire et vos objectifs.
                        </p>
                        <p class="filled" id="dev-description">
                            @php
                                $description = preg_replace('((https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,}))',
                                    '<a target="_blank" href="$1">$1</a>',
                                    $context['description']);
                            @endphp
                            {!! ($description) !!}
                        </p>
                    </div>
                    <div class="col-md-6 editable pratiques edition">
                        <div class="d-flex align-items-center">
                            <h3 class="font-weight-bold d-inline-block">Mes pratiques</h3>
                            <!--div class="edit d-inline-block ml-4 mb-1">
                                <a class="btn btn-outline-gray edit-btn mr-2" data-toggle="modal" data-target="#pratiquesEdit">
                             <span class="material-icons text-dark-green">
                                 edit
                            </span>
                                </a>
                                <a class="text-dark-green edit-link text-decoration-none">
                                    Editer
                                </a>
                            </div-->
                        </div>
                        <p class="empty d-none">Renseignez les pratiques misent en œuvre sur votre exploitation.</p>
                        <div class="timeline filled">
                            @php $count = 0; @endphp
                            @if(!empty($practises))
                                @foreach($practises as $year => $practisesByYear)
                                    @php $count++; @endphp
                                    <div class="year practises-elem">{{$year}}</div>
                                    <ul class="elements practises-elem">
                                        @foreach($practisesByYear as $practise)
                                            <li class="practises-elem">
                                                <a target="_blank" href="{{config('neayi.wiki_url').'/index.php?curid='.$practise['page_id']}}">
                                                    {{$practise['label']}}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endforeach
                            @else
                                <div class="alert alert-info">
                                    Cliquez ici pour accéder à la liste des pratiques Triple Performance, et trouver celles qui conviennent à votre système !
                                </div>
                            @endif
                        </div>
                        @if(!empty($practises))
                            <button class="btn btn-outline-gray mt-2" id="btn-show-practises" action="show">
                                Afficher toutes mes pratiques
                            </button>
                        @endif
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
                                <!--div class="row mt-4">
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
                                </div-->
                                <div class="row mt-4">
                                    <!--followed page -->
                                    @if(isset($interactions['follow']) && !empty($interactions['follow']))
                                        @include('users.profile.partials.interactions', ['interactionsPages' => $interactions['follow']])
                                    @else
                                        <div class="alert alert-info">
                                            Cliquez ici pour accéder à la liste des pratiques Triple Performance, et trouver celles qui conviennent à votre système !
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <div show-async="{{ route('profile.comments.show') }}"
                                 class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                            </div>
                            <div class="tab-pane fade following" id="third" role="tabpanel" aria-labelledby="third-tab">
                                <div class="row mt-4">
                                    <!--followed page -->
                                    @if(isset($interactions['applause']) && !empty($interactions['applause']))
                                        @include('users.profile.partials.interactions', ['interactionsPages' => $interactions['applause']])
                                    @else
                                        <div class="alert alert-info">
                                            Cliquez ici pour accéder à la liste des pratiques Triple Performance, et trouver celles qui conviennent à votre système !
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('users.profile.modals.add-characteristics')
    @include('users.profile.modals.farming-edit')
    @include('users.profile.modals.header-edit')
    @include('users.profile.modals.my-practices')
    @include('users.profile.modals.characteristics-search')
@endsection
