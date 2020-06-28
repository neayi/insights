@extends('adminlte::page')

@section('title', __('pages.title_invite_users'))

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
                                {{$usersToProcess['imported']}} @lang('organizations.message.number_imported_users')
                            </p>
                        </div>
                        @if($usersToProcess['error'] !== 0)
                            <div class="callout callout-danger">
                                <p>
                                    {{$usersToProcess['error']}} @lang('organizations.message.lines_errors')
                                </p>
                            </div>
                        @endif
                        <table class="table table-hover text-nowrap">
                            <thead>
                                <tr>
                                    <th>@lang('organizations.table.invitation.email')</th>
                                    <th>@lang('organizations.table.invitation.firstname')</th>
                                    <th>@lang('organizations.table.invitation.lastname')</th>
                                    <th>@lang('organizations.table.invitation.comment')</th>
                                </tr>
                            </thead>

                            @foreach($usersToProcess['users'] as $user)
                            <tr>
                                <td>{{isset($user['email']) ? $user['email'] : ''}}</td>
                                <td>{{isset($user['firstname']) ? $user['firstname'] : ''}}</td>
                                <td>{{isset($user['lastname']) ? $user['lastname'] : ''}}</td>
                                <td>{{isset($user['error']) ? $user['error'] : ''}}</td>
                            </tr>
                            @endforeach
                        </table>
                    </div>
                    <div class="card-footer">
                        <form role="form" action="{{route('organization.users.invite')}}" method="post">
                            @csrf
                            <input type="hidden" value="{{$organization_id}}" name="organization_id">
                            <input type="hidden" value="{{json_encode($usersToProcess)}}" name="users">
                            <input type="submit" class="fa-pull-right btn btn-primary" value="@lang('organizations.action.invite_user_short')">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
