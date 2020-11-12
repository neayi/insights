@extends('adminlte::page')

@section('title', __('pages.title_list_users'))

@section('content')


<form class="form" role="form" action="{{ route('wizard.profile.process') }}">
    @include('users.wizard-profile.select-user-role')

    <div class="form-group">
        <input type="text" value="{{$firstname}}" class="form-control"/>
    </div>
    <div class="form-group">
        <input type="text" value="{{$lastname}}" class="form-control"/>
    </div>
</form>
@endsection
