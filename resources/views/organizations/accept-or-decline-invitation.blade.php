@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h5 class="card-title">@lang('organizations.message.you_have_been_invited', ['name' => $organization_to_join['name']])</h5>
                    </div>
                    <div class="card-body">
                        @if(isset($old_organisation))
                            <div class="callout callout-danger">
                                <h5>@lang('common.warning')</h5>
                                <p>
                                    @lang('organizations.already_in_a_organization', ['name' => $old_organisation['name']])
                                </p>
                            </div>
                        @endif
                        <a href="{{route('organization.user.join')}}" class="btn btn-success">@lang('organisations.action.join')</a>
                        <a href="{{route('home')}}" class="btn btn-danger">@lang('organisations.action.decline')</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
