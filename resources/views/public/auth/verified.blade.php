@extends('layouts.neayi.login')

@section('title', 'Votre email a bien été vérifié')

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
                                    Merci, votre email a bien été vérifié.<br/>

                                    @if(isset($callback) && $callback !== "")
                                        Vous allez être redirigés d'ici quelques secondes.
                                        Si rien ne se passe vous pouvez cliquer <a href="{{ $callback }}">içi</a>
                                    @else
                                        <a href="{{ config('neayi.wiki_url') }}">Consulter le wiki</a>
                                    @endif
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


