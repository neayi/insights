@extends('layouts.neayi.master')

@section('title', __('pages.wizard-profile'))

@section('content')

<form class="form" role="form" method="post" action="{{ route('wizard.profile.process') }}">
@csrf
    <div class="row cta-fixed-bar fixed-top grey-bg py-3 align-items-center">
        <div class="col-md-2 text-center">
            <strong>Présentez-vous</strong>
        </div>
        <div class="col-md-9 pl-4">
            <button type="submit" class="btn btn-dark-green text-white px-5 py-2 mr-2">Valider</button>
            <!--button type="button" class="btn btn-outline-darkgreen text-dark px-5 py-2">Valider et continuer de se présenter</button-->
        </div>
    </div>
    <div class="row py-5">
        <div class="col-md-8 offset-md-2 col-12">
            <span class="font-weight-bold text-dark-green text-big d-block mb-4">Merci !</span>
            <p class="mb-4">
                Pour vous aider à trouver des Retours d’expériences ou des Pratiques pouvant s’appliquer à votre exploitation et aider la communauté à vous comprendre,
            </p>
            <strong>
                Présentez-vous,
            </strong>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-2 offset-md-2">
            @include('users.wizard-profile.select-user-role')
        </div>
        <div class="col-lg-5">
            @include('users.wizard-profile.fill-identity')
        </div>
        <div class="col-lg-2">
            @include('users.wizard-profile.fill-postal-code')
        </div>
    </div>
    <div class="row mt-5 icon-checkboxes" id="select-farming" @if(old('role') !== "farmer") style="display: none;" @endif>
        <div class="col-lg-8 offset-md-2">
            <div class="form-group">
                <label class="label-big mb-3">Je suis principalement en</label>
                <ul class="d-md-flex">
                    @foreach($farmingType as $farming)
                        <li class="mr-4 ">
                            @include('users.wizard-profile.icon-farming', ['uuid' => $farming->uuid, 'code' => $farming->code])
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <button type="submit" class="btn btn-dark-green text-white px-5 py-2 mr-2">Valider</button>
            <!--button type="button" class="btn btn-outline-darkgreen text-dark px-5 py-2">Valider et continuer de se présenter</button-->
        </div>
    </div>
</form>
@endsection
