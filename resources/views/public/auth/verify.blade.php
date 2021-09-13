@extends('layouts.neayi.master')

@section('title', __('pages.wizard-profile'))

@section('content')

    @if(session('resent'))
        <div class="alert alert-success" role="alert">
            {{ __('adminlte::adminlte.verify_email_sent') }}
        </div>
    @endif

    <div class="col-lg-8 offset-lg-2 col-12 mt-lg-5" id="msg-err">
        <p class="mb-4" style="color: red;">
            @if(session()->has('from_forum'))
                {{ __('common.verify_from_forum') }}
            @else
                {{ __('adminlte::adminlte.verify_check_your_email') }}
                {{ __('adminlte::adminlte.verify_if_not_recieved') }}
            @endif
        </p>
    </div>


    <div class="row py-5 mb-4">
        <div class="col-lg-8 offset-lg-2 col-12" id="msg-err">
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <div class="row">
                    <div class="col-lg-3 offset-lg-2 col-md-4">
                        <button type="submit" class="btn btn-dark-green text-white px-5 py-2 mr-2">
                            {{ __('adminlte::adminlte.verify_request_another') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
