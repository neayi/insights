@extends('layouts.neayi.login')

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
                                        <h4 class="text-dark-green font-weight-bold mt-2">@lang('auth.reset_password')</h4>
                                    </div>
                                </div>
                                <form action="{{route('password.email')}}" method="POST">
                                    {{ csrf_field() }}
                                    @if (session('status'))
                                        <div class="alert alert-success message-success-reset">
                                            {{ session('status') }}
                                        </div>
                                    @endif
                                    <div class="row">
                                        <div class="col-md-10">
                                            <div class="form-group">
                                                <label for="email">@lang('common.email')</label>
                                                <input type="email" class="form-control" name="email" id="email" aria-describedby="emailHelp"
                                                       placeholder="@lang('auth.email_placeholder')">
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback" style="display: block !important;">
                                                        {{ $errors->first('email') }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row text-right mt-4">
                                        <div class="col-12">
                                            <a href="{{ route('login') }}" class="btn btn-link text-dark-green mr-4">@lang('auth.header_login')</a>
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
