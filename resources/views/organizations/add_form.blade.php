@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>@lang('pages.title_add_organization')</h1>
@stop

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">@lang('pages.title_add_organization')</h3>
                    </div>
                    <form role="form" method="POST" action="{{route('organization.add')}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="i-email">@lang('organizations.name') (*)</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="i-email" placeholder="@lang('organizations.name')" name="name">
                            </div>
                            <div class="form-group">
                                @error('name')
                                    <div class="alert alert-danger">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="exampleInputFile">@lang('organizations.picture')</label>
                                <div class="input-group">
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="exampleInputFile">
                                        <label class="custom-file-label" for="exampleInputFile">@lang('common.btn_chose_file')</label>
                                    </div>
                                    <div class="input-group-append">
                                        <span class="input-group-text" id="">Upload</span>
                                    </div>
                                </div>
                            </div>
                            <div class="alert alert-info">
                                Best 400 * 600 px
                            </div>
                            <div class="form-group">
                                <label for="address1">@lang('organizations.address1') (*)</label>
                                <input type="text" name="address1" class="form-control @error('address1') is-invalid @enderror" id="address1" placeholder="@lang('organizations.address1')">
                            </div>
                            <div class="form-group">
                                @error('address1')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="address2">@lang('organizations.address2') (*)</label>
                                <input type="text" name="address2" class="form-control @error('address2') is-invalid @enderror" id="address2" placeholder="@lang('organizations.address2')">
                            </div>
                            <div class="form-group">
                                @error('address2')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="city">@lang('organizations.city') (*)</label>
                                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" id="city" placeholder="@lang('organizations.city')">
                            </div>
                            <div class="form-group">
                                @error('city')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="pc">@lang('organizations.pc') (*)</label>
                                <input type="text" name="pc" class="form-control @error('pc') is-invalid @enderror" id="pc" placeholder="@lang('organizations.pc')">
                            </div>
                            <div class="form-group">
                                @error('pc')
                                <div class="alert alert-danger">
                                    {{ $message }}
                                </div>
                                @enderror
                            </div>
                        </div>
                        <div class="card-footer fa-pull-right">
                            <button type="submit" class="btn btn-primary">@lang('common.btn_confirm')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

