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
                        <div class="callout callout-danger">
                            <h5>@lang('common.warning')</h5>
                            <p>
                                @lang('organizations.message.should_be_log_with_other_account')
                            </p>
                        </div>
                        <form method="post" action="{{route('logout')}}">
                            @csrf
                            <button class="btn btn-success">@lang('organizations.message.logout_to_join_organization')</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
