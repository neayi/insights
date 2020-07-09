@extends('adminlte::master')

@section('adminlte_css')
    @stack('css')
    @yield('css')
@stop

@section('classes_body', 'register-page')

@php( $login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login') )
@php( $register_url = View::getSection('register_url') ?? config('adminlte.register_url', 'register') )
@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
    @php( $login_url = $login_url ? route($login_url) : '' )
    @php( $register_url = $register_url ? route($register_url) : '' )
    @php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
    @php( $login_url = $login_url ? url($login_url) : '' )
    @php( $register_url = $register_url ? url($register_url) : '' )
    @php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('body')
    <div class="register-box">
        <div class="register-logo">
            <a href="{{ $dashboard_url }}">{!! config('adminlte.logo') !!}</a>
        </div>
        <div class="card">
            <div class="card-header">
                <a href="{{ route('register.auth.provider', ['provider' => 'facebook']) }}" class="btn btn-block bg-gradient-primary">Facebook</a>
                <a href="{{ route('register.auth.provider', ['provider' => 'google']) }}" class="btn btn-block bg-gradient-primary">Google</a>
                <a href="{{ route('register.auth.provider', ['provider' => 'twitter']) }}" class="btn btn-block bg-gradient-primary">Twitter</a>
            </div>
            <div class="card-body register-card-body">
                <p class="login-box-msg">{{ __('adminlte::adminlte.register_message') }}</p>
                <form action="{{ route('auth.register-social-network') }}" method="post">
                    {{ csrf_field() }}
                    <input type="hidden" name="provider" value="{{old('provider')}}">
                    <input type="hidden" name="provider_id" value="{{old('provider_id')}}">
                    <input type="hidden" name="picture_url" value="{{old('picture_url')}}">
                    <div class="input-group mb-3">
                        <input type="text" name="firstname" class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}" value="{{ old('firstname') }}"
                               placeholder="{{ __('adminlte::adminlte.firstname') }}" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>

                        @if ($errors->has('firstname'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('firstname') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="input-group mb-3">
                        <input type="text" name="lastname" class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}" value="{{ old('lastname') }}"
                               placeholder="{{ __('adminlte::adminlte.lastname') }}" autofocus>
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user"></span>
                            </div>
                        </div>

                        @if ($errors->has('lastname'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('lastname') }}</strong>
                            </div>
                        @endif
                    </div>
                    <div class="input-group mb-3">
                        <input type="email" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" value="{{ old('email') }}"
                               placeholder="{{ __('adminlte::adminlte.email') }}">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-envelope"></span>
                            </div>
                        </div>
                        @if ($errors->has('email'))
                            <div class="invalid-feedback">
                                <strong>{{ $errors->first('email') }}</strong>
                            </div>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-primary btn-block btn-flat">
                        {{ __('adminlte::adminlte.register') }}
                    </button>
                </form>
                <p class="mt-2 mb-1">
                    <a href="{{ $login_url }}">
                        {{ __('adminlte::adminlte.i_already_have_a_membership') }}
                    </a>
                </p>
            </div>
        </div>
    </div>
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop
