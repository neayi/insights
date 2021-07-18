@extends('adminlte::page')

@section('title', __('pages.title_edit_organization'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{$organization['url_picture']}}">
                        </div>
                        <h3 class="profile-username text-center">{{ $organization['name'] }}</h3>
                        <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Followers</b> <a class="float-right">1,322</a>
                            </li>
                            <li class="list-group-item">
                                <b>Following</b> <a class="float-right">543</a>
                            </li>
                            <li class="list-group-item">
                                <b>Friends</b> <a class="float-right">13,287</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="card-header p-2">
                        <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab" style="">@lang('organizations.nav_btn_edit')</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form role="form" class="form-horizontal" action="{{ route('organization.edit', ['id' => $organization['uuid']]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">@lang('organizations.name') (*)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" name="name" id="inputName" placeholder="@lang('organizations.name')"
                                                   value="{{old('name', $organization['name'])}}">
                                            @error('name')
                                                <div class="invalid-feedback" style="display: block !important;">
                                                    <span id="exampleInputEmail1-error" class="error invalid-feedback" style="display: block !important;">
                                                        {{ $message }}
                                                    </span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-file">
                                            <input name="logo" type="file" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile">@lang('organizations.picture')</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">@lang('organizations.address1') (*)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control {{ $errors->has('address1') ? 'is-invalid' : '' }}" name="address1" id="inputName" placeholder="@lang('organizations.address1')"
                                                   value="{{old('address1', $organization['address1'])}}">
                                            @error('address1')
                                            <div class="invalid-feedback" style="display: block !important;">
                                                <span id="exampleInputEmail1-error" class="error invalid-feedback" style="display: block !important;">
                                                    {{ $message }}
                                                </span>
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">@lang('organizations.address2') </label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control {{ $errors->has('address2') ? 'is-invalid' : '' }}" name="address2" id="inputName" placeholder="@lang('organizations.address2')"
                                                   value="{{old('address2', $organization['address2'])}}">
                                            @error('address2')
                                            <div class="invalid-feedback" style="display: block !important;">
                                                <span id="exampleInputEmail1-error" class="error invalid-feedback" style="display: block !important;">
                                                    {{ $message }}
                                                </span>
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">@lang('organizations.city') (*)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" name="city" id="inputName" placeholder="@lang('organizations.city')"
                                                   value="{{old('city', $organization['city'])}}">
                                            @error('city')
                                            <div class="invalid-feedback" style="display: block !important;">
                                                <span id="exampleInputEmail1-error" class="error invalid-feedback" style="display: block !important;">
                                                    {{ $message }}
                                                </span>
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">@lang('organizations.pc') (*)</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control {{ $errors->has('pc') ? 'is-invalid' : '' }}" name="pc" id="inputName" placeholder="@lang('organizations.pc')"
                                                   value="{{old('pc', $organization['postal_code'])}}">
                                            @error('pc')
                                            <div class="invalid-feedback" style="display: block !important;">
                                                <span id="exampleInputEmail1-error" class="error invalid-feedback" style="display: block !important;">
                                                    {{ $message }}
                                                </span>
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">@lang('common.btn_confirm')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bs-custom-file-input/1.3.4/bs-custom-file-input.js"></script>
    <script>
        $(document).ready(function () {
            bsCustomFileInput.init();
        });
    </script>
@endsection
