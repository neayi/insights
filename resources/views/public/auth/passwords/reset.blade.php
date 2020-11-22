@extends('layouts.neayi.empty-layout')

@section('title', __('pages.wizard-profile'))

@section('content')

    <div class="pt-3">
        <div class="modal fade modal-bg show d-block " id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
            <div class="modal-dialog modal-lg mx-0 mx-sm-auto" role="document">
                <div class="modal-content p-md-3 p-1">
                    <div class="modal-body pt-4">
                        <div class="container-fluid">
                            <div class="row">
                                @include('public.auth.partials.reinsurance')
                                <div class="col-lg-6 offset-lg-2 bg-white-mobile">
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <h4 class="text-dark-green font-weight-bold mt-2">Réinitialisation du mot de passe</h4>
                                        </div>
                                    </div>
                                    <form action="{{route('password.update')}}" method="POST">
                                        <input type="hidden" name="token" value="{{ $token }}">
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp" placeholder="Votre adresse email">
                                                    @if ($errors->has('email'))
                                                        <div class="invalid-feedback" style="display: block !important;">
                                                            {{ $errors->first('email') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Mot de passe</label>
                                                    <div id="show_hide_password">
                                                        <input class="form-control" name="password" type="password" placeholder="8 caractères minimum">
                                                        <div class="form-icon">
                                                            <a href=""><span class="material-icons" aria-hidden="true">visibility</span></a>
                                                        </div>
                                                    </div>
                                                    @if ($errors->has('password'))
                                                        <div class="invalid-feedback" style="display: block !important;">
                                                            {{ $errors->first('password') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label>Confirmation mot de passe</label>
                                                    <div id="show_hide_password">
                                                        <input class="form-control" name="password_confirmation" type="password" placeholder="8 caractères minimum">
                                                    </div>
                                                    @if ($errors->has('password_confirmation'))
                                                        <div class="invalid-feedback" style="display: block !important;">
                                                            {{ $errors->first('password_confirmation') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row text-right mt-4">
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-dark-green text-white px-5 py-2">Valider</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
