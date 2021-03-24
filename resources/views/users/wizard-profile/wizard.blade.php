@extends('layouts.neayi.master')

@section('title', __('pages.wizard-profile'))

@section('content')

<form class="form" role="form" method="post" action="{{ route('wizard.profile.process') }}">
@csrf
    <div class="row cta-fixed-bar fixed-top grey-bg py-3 align-items-center mw-100">
        <div class="col-lg-3 offset-lg-2 col-md-4">
            <button type="button" class="btn btn-dark-green text-white px-5 py-2 mr-2 w-100 mb-2 mb-md-0 ml-3">Valider</button>
        </div>
        <!--div class="col-lg-5 col-md-6">
            <button type="button" class="btn btn-outline-darkgreen text-dark px-5 py-2 w-100 ml-3">Valider et continuer de se présenter</button>
        </div-->
    </div>
    <div class="row py-5">
        @if(empty($errors->any()))
            <div class="col-lg-8 offset-lg-2 col-12" id="msg">
                <span class="font-weight-bold text-dark-green text-big d-block mb-4">Merci !</span>
                <p class="mb-4">
                    Pour vous aider à trouver des retours d’expériences ou des pratiques pouvant s’appliquer
                    à votre exploitation et aider la communauté à vous comprendre, présentez-vous,
                </p>
            </div>
        @endif
        @if(isset($errors) && $errors->any())
            <div class="col-lg-8 offset-lg-2 col-12" id="msg-err">
                <p class="mb-4" style="color: red;">
                    Vous y êtes presque ! Il nous manque deux trois infos dans le formulaire pour pouvoir continuer !
                </p>
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-lg-2 offset-lg-2">
            @include('users.wizard-profile.select-user-role')
        </div>
        <div class="col-lg-5">
            @include('users.wizard-profile.fill-identity')
        </div>
        <div class="col-lg-2">
            @include('users.wizard-profile.fill-postal-code')
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12 offset-lg-2">
            @include('users.wizard-profile.fill-email')
        </div>
    </div>
    <div class="row mt-5 icon-checkboxes" id="select-farming">
        <div class="col-lg-8 offset-lg-2">
            <div class="form-group">
                <label class="label-big mb-3">Mes productions</label>
                <div class="circle-row d-flex flex-wrap">
                @foreach($farmingTypeMain as $farming)
                        <div class="circle-item">
                        @include('users.wizard-profile.icon-farming', [
                                 'uuid' => $farming['uuid'],
                                 'code' => $farming['code'],
                                 'icon' => $farming['icon'],
                                 'label' => $farming['pretty_page_label'],
                             ])
                        </div>
                    @endforeach
                    <div class="circle-item" id="more-main-production">
                        <label for="myCheckbox23">
                            <img src="{{asset('icons/More.svg')}}" class="rounded-circle mb-2" />
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-8 offset-md-2" id="second-row-main-production" style="display: none;">
            <div class="form-group">
                <div class="circle-row d-flex flex-wrap">
                    @foreach($farmingType as $farming)
                        <div class="circle-item">
                            @include('users.wizard-profile.icon-farming', [
                                 'uuid' => $farming['uuid'],
                                 'code' => $farming['code'],
                                 'icon' => $farming['icon'],
                                 'label' => $farming['pretty_page_label'],
                             ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="col-lg-8 offset-lg-2">
            <div class="form-group">
                <label class="label-big mb-3">Mon cahier des charges</label>
                <div class="circle-row d-flex flex-wrap">
                @foreach($croppingTypeMain as $farming)
                        <div class="circle-item">
                        @include('users.wizard-profile.icon-farming', [
                                 'uuid' => $farming['uuid'],
                                 'code' => $farming['code'],
                                 'icon' => $farming['icon'],
                                 'label' => $farming['pretty_page_label'],
                             ])
                        </div>
                    @endforeach
                    @if(!empty($croppingType))
                        <div class="circle-item" id="more-main-cropping">
                            <label for="myCheckbox23">
                                <img src="{{asset('icons/More.svg')}}" class="rounded-circle mb-2" />
                            </label>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-lg-8 offset-md-2" id="second-row-main-cropping" style="display: none;">
            <div class="form-group">
                <div class="circle-row d-flex flex-wrap">
                    @foreach($croppingType as $farming)
                        <div class="circle-item">
                            @include('users.wizard-profile.icon-farming', [
                                 'uuid' => $farming['uuid'],
                                 'code' => $farming['code'],
                                 'icon' => $farming['icon'],
                                 'label' => $farming['pretty_page_label'],
                             ])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-3 offset-lg-2 col-md-4">
            <button type="submit" class="btn btn-dark-green text-white px-5 py-2 mr-2">Valider</button>
        </div>
        <!--div class="col-lg-5 col-md-6">
            <button type="button" class="btn btn-outline-darkgreen text-dark px-5 py-2 w-100">Valider et continuer de se présenter</button>
        <div-->
    </div>
</form>
@endsection
