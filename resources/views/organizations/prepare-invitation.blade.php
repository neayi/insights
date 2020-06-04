@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>@lang('pages.title_invite_users')</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">@lang('pages.invite_users')</h3>
                    </div>
                    <div class="card-body">
                        <div class="callout callout-info">
                            <p>
                                {{count($usersToProcess)}} utilisateur(s) seront invités à rejoindre l'organisme
                            </p>
                        </div>
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Prénom</th>
                                    <th>Nom</th>
                                </tr>
                            </thead>
                        @php
                            $count = count($usersToProcess) > 10 ? 10 : count($usersToProcess);
                        @endphp

                            @for($i=0; $i < $count; $i++)
                            <tr>
                                <td>{{isset($usersToProcess[$i]['email']) ? $usersToProcess[$i]['email'] : ''}}</td>
                                <td>{{isset($usersToProcess[$i]['firstname']) ? $usersToProcess[$i]['firstname'] : ''}}</td>
                                <td>{{isset($usersToProcess[$i]['lastname']) ? $usersToProcess[$i]['lastname'] : ''}}</td>
                            </tr>
                        @endfor
                        </table>
                    </div>
                    <div class="card-footer">
                        <form role="form" action="{{route('organization.users.invite')}}" method="post">
                            @csrf
                            <input type="hidden" value="{{$organization_id}}" name="organization_id">
                            <input type="hidden" value="{{json_encode($usersToProcess)}}" name="users">
                            <input type="submit" class="fa-pull-right btn btn-primary" value="Inviter">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
