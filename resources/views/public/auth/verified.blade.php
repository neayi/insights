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

                            <div class="col-lg-6 align-self-center offset-lg-2 bg-white-mobile">
                                <p>Merci, votre email a bien été vérifié. <span class="material-icons" style="vertical-align: text-bottom;color: #15A072;">gpp_good</span></p>
                                @if(isset($callback) && $callback !== "")
                                <p>Vous allez être redirigés d'ici quelques secondes.</p>
                                <p> Si rien ne se passe vous pouvez cliquer <a href="{{ $callback }}">içi</a>.</p>
                                @else
                                <p><a href="{{ config('neayi.wiki_url') }}">Consulter le wiki</a></p>
                                @endif
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


