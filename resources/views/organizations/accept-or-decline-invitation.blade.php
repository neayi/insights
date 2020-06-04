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
                        @if(isset($old_organisation))
                            <div class="callout callout-danger">
                                <h5>Attention ! </h5>
                                <p>
                                    Vous faites déjà parti de l'organisme : {{ $old_organisation['name'] }}, en rejoindre un nouveau
                                    vous empéchera d'accèder à votre ancien environnement (segment, ...)
                                </p>
                            </div>
                        @endif
                        <a href="{{route('organization.user.join')}}" class="btn btn-success">Rejoindre</a>
                        <a href="{{route('home')}}" class="btn btn-danger">Décliner</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
