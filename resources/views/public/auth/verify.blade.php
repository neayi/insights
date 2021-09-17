@extends('layouts.neayi.master')

@section('title', __('pages.wizard-profile'))

@section('content')

    @if(session('resent'))
        <div class="alert alert-success mx-auto my-4 text-center font-weight-bold" role="alert" style="max-width: 400px;">
            {{ __('adminlte::adminlte.verify_email_sent') }}
        </div>
    @endif

    <div class="col-lg-8 offset-lg-2 col-12 mt-lg-5" id="msg-err">
        <p class="mb-4 text-center" style="font-weight: bold">
            {{ __('adminlte::adminlte.verify_check_your_email') }}
        </p>
        
        <p class="text-center"><img style="max-width:100%" src="{{asset('images/verify-email.png')}}" alt="" class=""/></p>
    </div>


    <div class="row py-5 mb-4">
        <div class="col-lg-8 offset-lg-2 col-12" id="msg-err">
            <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                @csrf
                <p class="mb-4 text-center text-muted">
                    {{ __('adminlte::adminlte.verify_if_not_recieved') }}
                </p>
                <div class="row">
                    <div class="text-center col-lg-5 col-md-7 col-10 mb-5 mx-auto">
                        <button type="submit" class="btn btn-dark-green text-white px-5 py-2">
                            {{ __('adminlte::adminlte.verify_request_another') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@stop
