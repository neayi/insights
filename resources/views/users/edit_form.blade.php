@extends('adminlte::page')

@section('title', __('pages.title_edit_user'))

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-3">
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img class="profile-user-img img-fluid img-circle" src="{{$user['url_picture']}}" alt="User profile picture">
                        </div>
                        <h3 class="profile-username text-center">{{ ucfirst($user['firstname']).' '.ucfirst($user['lastname']) }}</h3>
                        <p class="text-muted text-center">{{ isset($organization['name']) ? $organization['name'] : '' }}</p>
                        <ul class="list-group list-group-unbordered mb-3">
                            @if(in_array('admin', $user['roles']))
                                <li class="list-group-item">
                                    <b>Role </b> <a class="float-right"><span class="badge btn-danger">Admin</span></a>
                                </li>
                            @endif
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
                            <li class="nav-item"><a class="nav-link active" href="#settings" data-toggle="tab" style="">Editer</a></li>
                            <li class="nav-item"><a class="nav-link" href="#rights" data-toggle="tab" style="">Droits de l'utilisateur</a></li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="tab-pane active" id="settings">
                                <form role="form" class="form-horizontal" action="{{ route('user.edit', ['id' => $user['uuid']]) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <label for="inputName" class="col-sm-2 col-form-label">Prénom</label>
                                        <div class="col-sm-10">
                                            <input type="text" class="form-control {{ $errors->has('firstname') ? 'is-invalid' : '' }}" name="firstname" id="inputName" placeholder="Prénom"
                                                   value="{{old('firstname', $user['firstname'])}}">
                                            @error('firstname')
                                            <div class="invalid-feedback" style="display: block !important;">
                                                <span id="exampleInputEmail1-error" class="error invalid-feedback" style="display: block !important;">
                                                    {{ $message }}
                                                </span>
                                            </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputEmail" class="col-sm-2 col-form-label">Nom</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="lastname" class="form-control {{ $errors->has('lastname') ? 'is-invalid' : '' }}" id="inputEmail" placeholder="Nom"
                                                   value="{{old('lastname', $user['lastname'])}}">
                                            @error('lastname')
                                                <div class="invalid-feedback" style="display: block !important;">
                                                    <span id="exampleInputEmail1-error" class="error invalid-feedback" style="display: block !important;">
                                                        {{ $message }}
                                                    </span>
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="inputName2" class="col-sm-2 col-form-label">Email</label>
                                        <div class="col-sm-10">
                                            <input type="text" name="email" class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="inputName2" placeholder="Email"
                                                   value="{{old('email', $user['email'])}}">
                                            @error('email')
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
                                            <label class="custom-file-label" for="customFile">Avatar de l'utilisateur</label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="offset-sm-2 col-sm-10">
                                                <button type="submit" class="btn btn-primary">@lang('common.btn_confirm')</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="tab-pane" id="rights">
                                <form role="form" class="form-horizontal" method="post"
                                      action="{{ route('user.grant-admin.organization', ['id' => $user['uuid'], 'organization' => $user['organization_id']]) }}">
                                      @csrf
                                      <input type="submit" value="Mettre admin" class="btn btn-danger"/>
                                </form>
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
