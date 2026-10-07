@extends('layouts.neayi.login')

@section('title', __('pages.register'))

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
                                        <h4 class="text-dark-green font-weight-bold mt-2">@lang('auth.create_account')</h4>
                                    </div>
                                </div>
                                <form action="{{ route('auth.register-social-network') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="provider" value="{{ old('provider') }}">
                                    <input type="hidden" name="provider_id" value="{{ old('provider_id') }}">
                                    <input type="hidden" name="picture_url" value="{{ old('picture_url') }}">
                                    @foreach (['firstname' => 'text', 'lastname' => 'text', 'email' => 'email'] as $field => $type)
                                        <div class="row">
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <label for="{{ $field }}">@lang('common.'.$field)</label>
                                                    <input value="{{ old($field) }}" type="{{ $type }}" class="form-control" name="{{ $field }}" id="{{ $field }}">
                                                    @if ($errors->has($field))
                                                        <div class="invalid-feedback" style="display: block !important;">
                                                            {{ $errors->first($field) }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="row text-right mt-4">
                                        <div class="col-12">
                                            <a href="{{ route('login') }}" class="btn btn-link text-dark-green mr-4">@lang('auth.already_account')</a>
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
