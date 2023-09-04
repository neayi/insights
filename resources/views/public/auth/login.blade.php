@extends('layouts.neayi.login')

@section('title', __('pages.login'))

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
                                        <h4 class="text-dark-green font-weight-bold mt-2">@lang('auth.header_login')</h4>
                                    </div>
                                </div>
                                <div class="row mt-2 mb-4">
                                    <div class="col-md-12 login-with-rs pr-0">
                                        <div class="row">
                                            <div class="col-lg-6 col-sm-12">
                                                <h5 class="text-dark-purple font-weight-bold d-inline-block mr-1">
                                                    @lang('auth.modal.create-account-with-social-network')
                                                </h5>
                                            </div>
                                            <div class="col-lg-6 social-buttons text-lg-right col-sm-12 text-center">
                                                <a href="{{ route('auth.provider', ['provider' => 'facebook']) }}">
                                                    <img src="images/facebook-logo.png" alt="@lang('auth.alt_register_facebook')" class="d-inline-block d-inline-block mr-1 mr-md-2">
                                                </a>
                                                {{-- <a href="{{ route('auth.provider', ['provider' => 'twitter']) }}">
                                                    <img src="images/twitter-logo.png" alt="@lang('auth.alt_register_twitter')" class="d-inline-block mr-1 ml-1 mr-md-2 ml-md-2">
                                                </a> --}}
                                                <a href="{{ route('auth.provider', ['provider' => 'google']) }}">
                                                    <img src="images/google-logo.png" alt="@lang('auth.alt_register_google')" class="d-inline-block ml-1 ml-md-2 mr-md-2">
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row mb-3 mt-2">
                                    <div class="col-md-12">
                                        <h5 class="text-dark-purple font-weight-bold">
                                            @lang('auth.modal.create-account-with-your-email')
                                        </h5>
                                    </div>
                                </div>
                                <form method="POST" action="/login">
                                    {{ csrf_field() }}
                                    <input type="checkbox" name="remember" checked style="display: none;">
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="email">Email</label>
                                                <input name="email" type="email" value="{{ old('email') }}" class="form-control" id="email" aria-describedby="emailHelp"
                                                       placeholder="@lang('auth.email_placeholder')">
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
                                                    <input class="form-control" type="password" name="password" placeholder="@lang('auth.rule_password_length')">
                                                    <div class="form-icon">
                                                        <a class="eye" href=""><span class="material-icons" aria-hidden="true">visibility</span></a>
                                                    </div>
                                                    <div>
                                                        <a style="font-size: x-small" href="{{ route('password.request') }}">
                                                           @lang('auth.forgot_password')
                                                        </a>
                                                    </div>
                                                    @if ($errors->has('password'))
                                                        <div class="invalid-feedback" style="display: block !important;">
                                                            {{ $errors->first('password') }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row text-right mt-4">
                                        <div class="col-12">
                                            <a href="{{ route('register') }}" class="btn btn-link text-dark-green mr-4">@lang('auth.no_account')</a>
                                            <button type="submit" class="btn btn-dark-green text-white px-5 py-2">@lang('common.btn_validate')</button>
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
