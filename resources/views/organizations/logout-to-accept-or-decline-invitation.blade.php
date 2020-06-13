@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">Vous avez été invité à rejoindre l'organisme : {{ $organization_to_join['name'] }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="callout callout-danger">
                            <h5>Attention ! </h5>
                            <p>
                                Vous devez vous connecter avec le bon compte...
                            </p>
                        </div>
                        <form method="post" action="{{route('logout')}}">
                            @csrf
                            <button class="btn btn-success">Se déconnecter pour rejoindre l'organisme</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
