@extends('layouts.neayi.master')

@section('title', __('pages.wizard-profile'))

@section('content')

<form class="form" role="form" method="post" action="{{ route('wizard.profile.process') }}">
    @csrf
    <div class="container-fluid">
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
                <div class="form-group">
                    <label id="state-role" class="label-big required mb-3">Je suis</label>
                    @include('users.wizard-profile.select-user-role')
                    <small class="form-text text-muted font-weight-semibold mt-2">
                        Nous sommes transparents sur le profil de ceux qui prennent la parole.
                    </small>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="form-group">
                    <label id="state-identity" class="label-big required mb-3">Je m'appelle</label>
                    <div class="form-row">
                        <div class="col-md-5">
                            <input type="text" name="firstname" class="input-identity form-control input-big" id="firstname" aria-describedby="" placeholder="Prénom">
                        </div>
                        <div class="col-md-7">
                            <input type="text" name="lastname" class="input-identity form-control input-big" id="surname" aria-describedby="" placeholder="Nom">
                        </div>
                    </div>
                    <small class="form-text text-muted font-weight-semibold mt-2">
                        Nous sommes convaincus qu’une personne qui s’exprime en utilisant sa véritable identité engage sa réputation. Notre communauté devient plus responsable et bienveillante.
                    </small>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="form-group">
                    <label id="state-postal" class="label-big required  mb-3">J'habite</label>
                    <div class="row align-items-center">
                        <div class="col-7">
                            <label>Code postal</label>
                        </div>
                        <div class="col-5 pl-0">
                            <input  type="text" id="input-postal" name="postal_code" class="form-control" placeholder="">
                        </div>
                    </div>
                    <small class="form-text text-muted font-weight-semibold mt-2">
                        Parce que nos pratiques ne sont pas partout les mêmes.
                    </small>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-8 offset-md-2">
                <div class="form-group">
                    <label class="label-big required mb-3">Je suis principalement en</label>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-8 offset-md-2">
                <button type="submit" class="btn btn-dark-green text-white px-5 py-2 mr-2">Valider</button>
                <!--button type="button" class="btn btn-outline-darkgreen text-dark px-5 py-2">Valider et continuer de se présenter</button-->
            </div>
        </div>
    </div>
</form>
@endsection
