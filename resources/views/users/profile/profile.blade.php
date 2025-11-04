@extends('layouts.neayi.master')

@section('title', __('pages.profile_visitor', ['user' => $context['fullname']]))

@section('content')
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}">

    <div class="container profile empty">
        <div class="row py-5">
            <div class="col-md-12">

                <!-- hero -->
                <div class="row">
                    <div class="col-md-3 d-none d-md-block @if($edit) editable @endif">
                    <input type="file" id="fileinput" name="picture" style="display: none;"/>
                    @if(empty($user['url_picture']))
                            <a href="#" class="text-decoration-none">
                                <div class="rounded-circle empty-avatar grey-bg">
                                    <div class="initials">
                                        {{ strtoupper(substr($user['firstname'], 0, 1).substr($user['lastname'], 0, 1)) }}
                                    </div>
                                    @if($edit)
                                        <span class="material-icons text-dark-green">
                                            add
                                        </span>
                                        <span class="add-avatar picture_upload">
                                            @lang('wiki_profile.add_avatar')
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @else
                            <div class="avatar-block">
                                <img src="{{ $user['url_picture'] }}" alt="Avatar auteur" class="rounded-circle avatar picture_upload">
                                @if($edit)
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
                                @endif
                            </div>
                        @endif
                    </div>

                    <div class="col-md-9 @if($edit) editable @endif">
                        <div class="row position-relative">
                            <div class="col-md-8 position-static">
                                <div class="d-flex align-items-center">
                                    <h2 class="d-inline-block mb-0">{{$context['fullname']}}</h2>
                                    @if($edit)
                                        <div class="edit d-inline-block ml-4 mt-1" data-toggle="modal" data-target="#headerEdit">
                                            <a class="btn btn-outline-gray edit-btn mr-2">
                                                 <span class="material-icons text-dark-green">
                                                     edit
                                                </span>
                                            </a>
                                            <a class="text-dark-green edit-link text-decoration-none">
                                                @lang('common.btn_edit')
                                            </a>
                                        </div>
                                    @endif
                                </div>
                                <div class="secteur font-weight-semibold">@lang('wiki_profile.'.$role)
                                     {{ !empty($context['sector']) ? '- '.$context['sector'] : '' }}
                                     {!! !empty($context['structure']) ? ' (<a href="' . $localesConfig[$context['default_locale']]['wiki_url'] . '/wiki/Structure:'.$context['structure'].'">'.$context['structure'] .'</a>)' : '' !!}
                                </div>
                                @if(!$edit && isset($more['discourse_username']) && $more['discourse_username'] !== null)
                                    <div>
                                        <a href="{{ env('DISCOURSE_URL').'/new-message?username='.$more['discourse_username'].'&title=&body=' }}"
                                           class="btn btn-dark-green text-white px-5 py-2 mr-2 mb-2 mb-md-0"><i class="fas fa-envelope"></i>
                                            @lang('wiki_profile.direct_message')
                                        </a>
                                    </div>
                                @endif

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
                                @if(isset($context['department']))
                                    <div><img src="{{ asset('images/map-france/France Climat Département '.$context['department'].'.svg') }}"></div>
                                    <div class="profile-dept-detail">
                                        @if(isset($context['characteristics_departement'][0]))
                                            <span class="dept-name">{{ $context['characteristics_departement'][0]->label }}</span><br>
                                            {{ $context['characteristics_departement'][0]->opt['climat'] }}
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- caractéristiques -->
                <div class="row mt-4">
                    <div class="col-md-12 @if($edit) editable @endif">
                        <div class="row position-relative">
                            <div class="col-12 position-static">
                                <div class="d-flex align-items-center">
                                    <h3 class="font-weight-bold d-inline-block">
                                        @include('users.profile.title.title-characteristics', ['edit' => $edit])
                                    </h3>
                                    @if($edit)
                                        <div class="edit d-inline-block ml-4 mb-1" data-toggle="modal" data-target="#caracteristiquesEdit">
                                            <a class="btn btn-outline-gray edit-btn mr-2">
                                             <span class="material-icons text-dark-green">
                                                 edit
                                            </span>
                                            </a>
                                            <a class="text-dark-green edit-link text-decoration-none">
                                                @lang('common.btn_edit')
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <div class="resume-exploitation w-100">
                                    <div class="resume-exploitation-container border-0">
                                        <div class="d-flex flex-wrap">
                                            @foreach($characteristics as $characteristic)
                                                @php
                                                    $characteristic = $characteristic->toArray();
                                                    $secondLine = null;
                                                    if(isset($characteristic['opt']) && isset($characteristic['opt']['climat'])){
                                                        $secondLine = '<br/>'.$characteristic['opt']['climat'];
                                                    }
                                                @endphp
                                                <div class="caracteristique-exploitation justify-content-between" >
                                                    <div>
                                                        @include('glyph.glyph', ['glyph' => $characteristic['icon'], 'type' => $characteristic['type']])
                                                    </div>
                                                    <a class="stretched-link" href="{{ $localesConfig[$characteristic['wiki']]['wiki_url'] }}/wiki/{{ $characteristic['page'] }}" target="_blank">
                                                        <span  class="span">{{ $characteristic['caption'] }} {!! $secondLine !!}</span>
                                                    </a>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- ma ferme -->
                <div class="row mt-4">
                    <div class="col-md-12 @if($edit) editable @endif exploitations-objectifs edition">
                        <div class="d-flex align-items-center">
                            <h3 class="font-weight-bold d-inline-block">
                                @include('users.profile.title.title-description', ['edit' => $edit])
                            </h3>
                            @if($edit)
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
                            @endif
                        </div>
                        <p class="empty d-none">
                            Présentez à la communauté votre ferme, son histoire et vos objectifs.
                        </p>
                        <p class="filled" id="dev-description">
                            @php
                                $description = preg_replace('((https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,}))',
                                    '<a target="_blank" href="$1">$1</a>',
                                    $context['description']);
                            @endphp
                            {!! $description !!}
                        </p>
                    </div>

                </div>

                <!-- mon activité -->
                <div class="row mt-4">
                    <div class="col-md-12">
                        <h3 class="font-weight-bold mb-3">
                            @include('users.profile.title.title-activity', ['edit' => $edit])
                        </h3>
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
                                    <!--followed page -->
                                    @if(isset($interactions['follow']) && !empty($interactions['follow']))
                                        @include('users.profile.partials.interactions', ['interactionsPages' => $interactions['follow']])
                                    @else
                                        <div class="alert alert-light small">
                                            Aucune page suivie encore !
                                        </div>
                                    @endif
                                </div>

                            </div>
                            <div show-async="{{ $routeComment }}"
                                 class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                            </div>
                            <div class="tab-pane fade following" id="third" role="tabpanel" aria-labelledby="third-tab">
                                <div class="row mt-4">
                                    <!--followed page -->
                                    @if(isset($interactions['applause']) && !empty($interactions['applause']))
                                        @include('users.profile.partials.interactions', ['interactionsPages' => $interactions['applause']])
                                    @else
                                        <div class="alert alert-light small">
                                            Aucune page applaudie encore ! ¯\_(ツ)_/¯
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

    @if($edit)
        @include('users.profile.modals.add-characteristics')
        @include('users.profile.modals.farming-edit')
        @include('users.profile.modals.header-edit')
        @include('users.profile.modals.my-practices')
        @include('users.profile.modals.characteristics-search')
    @endif
@endsection


