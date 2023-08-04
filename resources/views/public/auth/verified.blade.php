@extends('layouts.neayi.login')

@section('title', __('auth.email_verified'))

@section('content')
    <div class="modal fade modal-bg show d-block " id="loginModal" tabindex="-1" role="dialog" aria-labelledby="loginModal" aria-hidden="true">
        <div class="modal-dialog modal-lg mx-0 mx-sm-auto" role="document">
            <div class="modal-content p-md-3 p-1">
                <div class="modal-body pt-4">
                    <div class="container-fluid">
                        <div class="row">
                            @include('public.auth.partials.reinsurance')
                            <div class="col-lg-6 offset-lg-2 bg-white-mobile">
                                <div class="row mb-4 mt-4">
                                    <div class="col my-5">
                                        @lang('auth.email_verified')<br/>

                                        @if(isset($callback) && $callback !== "")
                                            @lang('auth.redirection')
                                            <a href="{{ $callback }}">
                                                @lang('common.here')
                                            </a>
                                        @else
                                            <a href="{{ \Illuminate\Support\Facades\Auth::user()->locale()->wiki_url }}">Consulter le wiki</a>
                                                @lang('common.consult_wiki')
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
        var callback = '{!! $callback !!}';
        if(callback !== ''){
            setTimeout(function(){
                window.location.href = callback;
            }, 5000);
        }
    </script>
@endsection


